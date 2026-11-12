<?php
namespace wss;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

// Constants
define("LOGGING", false);
define("SERVER_NAME", "websocketd");
define("SSH_STREAM_READ_MAX_LINES", 50);

class WsSSHConnector implements MessageComponentInterface
{
    protected $clients; // Holds connected client's data including ssh connection, command, pid and status of the process etc
    
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->rapi_clients = array();
    	$this->log_message("Initialised ".SERVER_NAME);
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->log_message("Connection {$conn->resourceId} opened for {$conn->remoteAddress}");
    }

    private function open_ssh_connection(ConnectionInterface $conn = NULL, $session_id, $token_id = NULL, $rand_key = NULL)
	{
		require_once('Net/SSH2.php');
		try
		{
			@$net_ssh = new \Net_SSH2("127.0.0.1", 22, 300);
			$browser_origin = ($conn != NULL && $token_id == NULL && $rand_key == NULL)? true: false;
			$token = $this->get_nsapi_token_from_session_file($session_id);
			if($token != NULL)
			{
				$token_split_index = ceil(strlen($token)/2);
				$user_name = substr($token, 0, $token_split_index);
				$password = substr($token, $token_split_index, $token_split_index);
				if(!@$net_ssh->login($user_name, $password))
				{
					$this->log_message("Connection {$conn->resourceId} : SSH login failed");
					$conn->send(json_encode(array('status' => "error", 'data' => "Not authorized to execute this command", 'type' => "ctrl")));
					$conn->close();
				}
				else
				{
					$this->log_message("Connection {$conn->resourceId} : SSH login successful");
					$data = new \StdClass;
					$data->ssh_connection = $net_ssh;
					$data->current_pid = NULL;
					$data->current_command = NULL;
					$data->check_status = false;
					$data->is_process_alive = false;
					$data->cli_shell_mode = "cli"; // Default

					if($browser_origin)
					{	
						$this->clients->attach($conn, $data); // Attach the connection and corresponding data for later use
					}	
					else
					{
						$data->rand_key = $rand_key;
						$data->timestamp = time();
						$this->rapi_clients[$token_id] = $data;
					}

					$net_ssh->setTimeout(1); // Read from the SSH stream after one second
					$buffer = $net_ssh->read('/[\z]/', NET_SSH2_READ_REGEX); // Read till end of the stream
					
					if($browser_origin)
					{	
						$conn->send(json_encode(array('status' => "done", 'data' => htmlentities($buffer), 'type' => "cmd")));
					}	
					else
					{	
						$conn->send(json_encode(array('status' => "done", 'data' => htmlentities($buffer), 'type' => "cmd", 'token' => $token_id)));
					}
				}

				return true;
			}
			else
			{	
				return false;
			}	
		}
		catch(\Exception $e)
		{
			$this->log_message("Connection {$conn->resourceId} : open_ssh_connection exception - {$e->getMessage()}");
		}	
	}
	
	private function get_nsapi_token_from_session_file($session_id)
	{
		$filename = "/var/nstmp/sess_{$session_id}"; // Session file

		if(file_exists($filename))
		{
			$session_data = file_get_contents($filename);
			preg_match('/NSAPI\|s:[0-9]+:\"([A-Za-z0-9#_]+)\";/i', $session_data, $matches); // Match NSAPI encoded session variable
			if(isset($matches[1]))
				return $matches[1];
		}
		
		return NULL;
	}
	
	public function onMessage(ConnectionInterface $conn, $msg)
	{
		$this->log_message("Connection {$conn->resourceId} : Received msg - {$msg}");
		$json_msg = json_decode($msg,true);

		if(isset($json_msg['origin']) && $json_msg['origin'] === "rapi" && isset($json_msg['rand_key']))
		{
			try
			{
				if(isset($json_msg['token']) && isset($this->rapi_clients[$json_msg['token']]) &&
					$json_msg['rand_key'] === $this->get_rand_key_for_rapi_client($json_msg['token'])) // Process message only if the connection is already attached
				{
					$token_id = $json_msg['token'];
					$net_ssh = $this->get_ssh_connection_for_rapi_client($token_id);

					if($net_ssh->isConnected())
					{
						$this->update_timestamp($token_id);
						$net_ssh->setTimeout(1);
						if($json_msg['type'] === "cmd")
						{
							$this->log_message("Connection {$conn->resourceId} : Received command - {$json_msg['data']}");

							$this->rapi_clients[$token_id]->check_status = isset($json_msg['check_status'])? $json_msg['check_status']:"false";
							
							if($this->rapi_clients[$token_id]->cli_shell_mode == "shell")
							{
								$net_ssh->write(escapeshellcmd($json_msg["data"])."\n"); // Execute command after escapeshellcmd in shell
							}
							else
							{
								if(strpos(strtolower($json_msg["data"]), "shell ") == false)
								{
									$net_ssh->write($json_msg["data"]."\n"); // Execute command in CLI. No need of escapeshellcmd.
								}
								else
								{
									$net_ssh->write(escapeshellcmd($json_msg["data"])."\n"); // Execute command in CLI in shell mode after escapeshellcmd
								}
							}

							if($this->rapi_clients[$token_id]->cli_shell_mode == "cli" && trim(strtolower($json_msg["data"])) == "shell")
							{
								$this->rapi_clients[$token_id]->cli_shell_mode = "shell";
							}
							else if($this->rapi_clients[$token_id]->cli_shell_mode == "shell" && trim($json_msg["data"]) == "exit")
							{
								$this->rapi_clients[$token_id]->cli_shell_mode = "cli";
							}	

							if($this->is_status_check_enabled_for_rapi_client($token_id))
							{
								if(isset($json_msg['pscomm']))
								{
									$comm = $json_msg['pscomm'];
								}
								else
								{
									$comm_arr = explode(' ',trim($json_msg["data"]));
									$comm = $comm_arr[0];
								}

								sleep(1);
								$this->set_current_pid_and_command_for_rapi_client($token_id, $comm);
							}
						}
						elseif($json_msg['type'] === "ctrl")
						{
							$net_ssh->write(escapeshellcmd($json_msg["data"])."\n");
							$conn->send(json_encode(array('status' => "killed", 'data' => "", 'type' => "ctrl")));
							$this->close_connection_for_rapi_client($token_id);
							return;
						}
						elseif($json_msg['type'] === "closessh")
						{
							$conn->send(json_encode(array('status' => "closed", 'data' => "", 'type' => "ctrl")));
							$this->close_connection_for_rapi_client($token_id);
							return;
						}

						$status = "done";
						if($this->is_status_check_enabled_for_rapi_client($token_id) && $this->is_process_alive_for_rapi_client($token_id))
							$status = "running";
						
						if($status === "running")
						{
							$buffer =""; // Initialize
							$ssh_stream_read_lines = 0; 
        					
        					while (true) 
        					{
								$ssh_stream_line_buffer = $net_ssh->read('/[\n]/', NET_SSH2_READ_REGEX); // Read one line

								if($ssh_stream_line_buffer == false) // Nothing to read
									break;

								$buffer = $buffer.$ssh_stream_line_buffer;
								
								$ssh_stream_read_lines++;
								if($ssh_stream_read_lines === SSH_STREAM_READ_MAX_LINES) // Read maximum lines
									break;
							}
						}	
						else
						{	
							$buffer = $net_ssh->read(); // Read till end of the stream fully
						}	

						$to_payload = array('status' => $status, 'data' => htmlentities($buffer));
						if(strlen($buffer) > 0)
						{
							$to_payload['type'] = "cmd";
						}
						else
							$to_payload['type'] = "relay";

						$conn->send(json_encode($to_payload));
					}
					else
					{
						$conn->send(json_encode(array('status' => "error", 'data' => "Connection Closed", 'type' => "ctrl")));
						
						$this->log_message("Connection {$conn->resourceId} : Connection Closed");
						
						$this->close_connection_for_rapi_client($token_id);
						$conn->close();
						unset($conn);
					}	
				}
				elseif($json_msg['type'] === "openssh")
				{
					$token_id = rand();

					while(isset($this->rapi_clients[$token_id]))
					{
						$token_id = rand();
					}

					// Removing stray ssh connections opened from rapi
					if(count($this->rapi_clients) > 5 ) // check for stray connections only if there are more than 5 rapi conns
					{
						$this->prune_rapi_connections();
					}

					if(!$this->open_ssh_connection($conn, $json_msg['data'], $token_id, $json_msg['rand_key']))
					{
						$conn->send(json_encode(array('status' => "error", 'data' => "Session expired or killed", 'type' => "ctrl")));
						
						$this->log_message("Connection {$conn->resourceId} : Session expired or killed");
						
						$conn->close();
						unset($conn);
					}
					else
					{
						$conn->send(json_encode(array('status' => "opened", 'data' => $token_id, 'type' => "ctrl")));
					}
				}
				else
				{
					$conn->send(json_encode(array('status' => "error", 'data' => "Connection Closed", 'type' => "ctrl")));
					
					$this->log_message("Connection {$conn->resourceId} : Connection Closed");
					
					$conn->close();
					unset($conn);
				}
			}
			catch(\Exception $e)
			{
				$this->log_message("Connection {$conn->resourceId} : RAPI websocket onMessage exception - {$e->getMessage()}");
			}				
		}
		else // Connections coming from Browsers that support websockets natively
		{
			try
			{
				if($this->clients->contains($conn)) // Process message only if the connection is already attached
				{
					$net_ssh = $this->get_ssh_connection($conn);
					if($net_ssh->isConnected())
					{
						$net_ssh->setTimeout(1);
						if($json_msg['type'] === "cmd")
						{
							$this->log_message("Connection {$conn->resourceId} : Received command {$json_msg['data']}");

							$this->clients[$conn]->check_status = isset($json_msg['check_status'])? $json_msg['check_status']:"false";

							if($this->clients[$conn]->cli_shell_mode == "shell")
							{
								$net_ssh->write(escapeshellcmd($json_msg["data"])."\n"); // Execute command after escapeshellcmd in shell
							}
							else
							{
								if(strpos(strtolower($json_msg["data"]), "shell ") == false)
								{
									$net_ssh->write($json_msg["data"]."\n"); // Execute command in CLI. No need of escapeshellcmd.
								}
								else
								{
									$net_ssh->write(escapeshellcmd($json_msg["data"])."\n"); // Execute command in CLI in shell mode after escapeshellcmd
								}
							}

							if($this->clients[$conn]->cli_shell_mode == "cli" && trim(strtolower($json_msg["data"])) == "shell")
							{
								$this->clients[$conn]->cli_shell_mode = "shell";
							}
							else if($this->clients[$conn]->cli_shell_mode == "shell" && trim($json_msg["data"]) == "exit")
							{
								$this->clients[$conn]->cli_shell_mode = "cli";
							}							

							if($this->is_status_check_enabled($conn))
							{
								if(isset($json_msg['pscomm']))
								{
									$comm = $json_msg['pscomm'];
								}
								else
								{
									$comm_arr = explode(' ',trim($json_msg["data"]));
									$comm = $comm_arr[0];
								}
								sleep(1);
								$this->set_current_pid_and_command($conn, $comm);
							}
						}
						elseif($json_msg['type'] === "ctrl")
						{
							$net_ssh->write(escapeshellcmd($json_msg["data"])."\n");
							$conn->send(json_encode(array('status' => "killed", 'data' => "", 'type' => "ctrl")));
							$conn->close();
							return;
						}

						$status = "done";
						if($this->is_status_check_enabled($conn) && $this->is_process_alive($conn))
							$status = "running";
						
						if($status === "running")
						{
							$buffer =""; // Initialize
							$ssh_stream_read_lines = 0; 
        					
        					while (true) 
        					{
								$ssh_stream_line_buffer = $net_ssh->read('/[\n]/', NET_SSH2_READ_REGEX); // Read one line

								if($ssh_stream_line_buffer == false) // Nothing to read
									break;

								$buffer = $buffer.$ssh_stream_line_buffer;
								
								$ssh_stream_read_lines++;
								if($ssh_stream_read_lines === SSH_STREAM_READ_MAX_LINES) // Read maximum lines
									break;
							}
						}	
						else
						{	
							$buffer = $net_ssh->read(); // Read till end of the stream fully
						}	

						$to_payload = array('status' => $status, 'data' => htmlentities($buffer));
						if(strlen($buffer) > 0)
						{
							$to_payload['type'] = "cmd";
						}
						else
							$to_payload['type'] = "relay";

						$conn->send(json_encode($to_payload));
					}
					else
					{
						$conn->send(json_encode(array('status' => "error", 'data' => "Connection Closed", 'type' => "ctrl")));
						
						$this->log_message("Connection {$conn->resourceId} : Connection Closed");

						$conn->close();
						unset($conn);
					}	
				}
				elseif($json_msg['type'] === "openssh")
				{
					if(!$this->open_ssh_connection($conn, $json_msg['data']))
					{
						$conn->send(json_encode(array('status' => "error", 'data' => "Session expired or killed", 'type' => "ctrl")));
						
						$this->log_message("Connection {$conn->resourceId} : Session expired or killed");
						
						$conn->close();
						unset($conn);
					}
				}
				else
				{
					$conn->send(json_encode(array('status' => "error", 'data' => "Connection closed", 'type' => "ctrl")));
					
					$this->log_message("Connection {$conn->resourceId} : Connection Closed");
					
					$conn->close();
					unset($conn);
				}
			}
			catch(\Exception $e)
			{
				$this->log_message("Connection {$conn->resourceId} : Native websocket onMessage exception - {$e->getMessage()}");
			}	
		}
		
    }

    public function onClose(ConnectionInterface $conn)
    {
    	if($this->clients->contains($conn))
    	{
	    	// The connection is closed, remove it, as we can no longer send it messages
			$net_ssh = $this->get_ssh_connection($conn);
			if($net_ssh->isConnected())
			{
				$net_ssh->disconnect();
			}
			$this->clients->detach($conn);
			unset($conn);
			
			$this->log_message("Connection {$conn->resourceId} : Connection Closed");	
    	}
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        if($this->clients->contains($conn))
    	{
	        $this->log_message("Connection {$conn->resourceId} : {$e->getMessage()}");

			$net_ssh = $this->get_ssh_connection($conn);
			if($net_ssh->isConnected())
			{
				$net_ssh->disconnect();
			}
			$this->clients->detach($conn);

			$this->log_message("Connection {$conn->resourceId} : Connection Closed");

			$conn->close();
			unset($conn);
		}
    }

    private function close_connection_for_rapi_client($token)
    {
    	if(isset($this->rapi_clients[$token]))
    	{
	    	// The connection is closed, remove it, as we can no longer send it messages
			$net_ssh = $this->get_ssh_connection_for_rapi_client($token);
			if($net_ssh->isConnected())
			{
				$net_ssh->disconnect();
			}
			unset($this->rapi_clients[$token]);
			
			$this->log_message("RAPI token {$token} : Connection Closed");	
    	}
    }
	
	private function set_current_pid_and_command(ConnectionInterface $conn , $command)
	{
		$this->clients[$conn]->current_pid = trim(shell_exec("ps -o lstart= -o pid= -o command= | grep ". escapeshellarg(trim($command)) ." | grep -v grep | sed 's/  */@/g' | sort -t '@' -k3,3 -k4,4 | head -n 1 | cut -d '@' -f6"));
		$this->clients[$conn]->current_command = $command;

		$this->log_message("Connection {$conn->resourceId} : Set Process ID for command - {$command} as - {$this->clients[$conn]->current_pid}");				
	}

	private function set_current_pid_and_command_for_rapi_client($token, $command)
	{
		$this->rapi_clients[$token]->current_pid = trim(shell_exec("ps -o lstart= -o pid= -o command= | grep ". escapeshellarg(trim($command)) ." | grep -v grep | sed 's/  */@/g' | sort -t '@' -k3,3 -k4,4 | head -n 1 | cut -d '@' -f6"));
		$this->rapi_clients[$token]->current_command = $command;

		$this->log_message("RAPI token {$token} : Set Process ID for command - {$command} as - {$this->rapi_clients[$token]->current_pid}");		
	}
	
	private function get_ssh_connection(ConnectionInterface $conn)
	{
		return $this->clients[$conn]->ssh_connection;
	}

	private function get_ssh_connection_for_rapi_client($token)
	{
		return $this->rapi_clients[$token]->ssh_connection;
	}
	
	private function get_rand_key_for_rapi_client($token)
	{
		return $this->rapi_clients[$token]->rand_key;
	}

	private function is_status_check_enabled(ConnectionInterface $conn)
	{
		return $this->clients[$conn]->check_status == "true";
	}

	private function is_status_check_enabled_for_rapi_client($token)
	{
		return $this->rapi_clients[$token]->check_status == "true";
	}
	
	private function get_current_pid(ConnectionInterface $conn)
	{
		return $this->clients[$conn]->current_pid;
	}

	private function get_current_pid_for_rapi_client($token)
	{
		return $this->rapi_clients[$token]->current_pid;
	}
	
	private function get_current_command(ConnectionInterface $conn)
	{
		return $this->clients[$conn]->current_command;
	}

	private function get_current_command_for_rapi_client($token)
	{
		return $this->rapi_clients[$token]->current_command;
	}

	private function update_timestamp($token)
	{
		$this->rapi_clients[$token]->timestamp = time();
	}

	private function prune_rapi_connections()
	{
		$current_timestamp = time();
		foreach ($this->rapi_clients as $id => $value)
		{
			if( $current_timestamp - $this->rapi_clients[$id]->timestamp > 1000 ) // If the connection is idle for 1000 seconds
			{
				$this->get_ssh_connection_for_rapi_client($id)->disconnect();
				unset($this->rapi_clients[$id]);
			}
		}
	}	
	
	private function is_process_alive(ConnectionInterface $conn)
	{
		if(ereg($this->get_current_command($conn),shell_exec("ps -p ". escapeshellarg($this->get_current_pid($conn)) ." -o command=")))
		{	
			$this->log_message("Connection {$conn->resourceId} : Process {$this->get_current_pid($conn)} alive for command - {$this->get_current_command($conn)}");	
			return true;
		}	
		else
		{	
			return false;
		}	
	}

	private function is_process_alive_for_rapi_client($token)
	{
		if(ereg($this->get_current_command_for_rapi_client($token),shell_exec("ps -p ". escapeshellarg($this->get_current_pid_for_rapi_client($token)) ." -o command=")))
		{	
			$this->log_message("RAPI token {$token} : Process {$this->get_current_pid_for_rapi_client($token)} alive for command - {$this->get_current_command_for_rapi_client($token)} ");				
			return true;
		}	
		else
		{	
			return false;
		}	
	}

	private function log_message($message)
    {
    	if(LOGGING)
    		file_put_contents("/var/log/websocketd.log", $message."\n", FILE_APPEND);

    	return;
    }
}
