


<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

/*TO DO handle special commands*/

    define('ROOT','/netscaler/ns_gui');

    define('BLOCKED','ssh,telnet');
    
    class Terminal{
        
        public $command          = '';
        public $output           = '';
        public $directory        = '';
		public $username 	 = '';
		public $password	 = '';        
		public $prepend_command  = '';
        // Holder for commands fired by system
        public $command_exec     = '';
        
        public function __construct(){
            if(!isset($_SESSION['dir'])){
                if(ROOT==''){
                    $this->command_exec = 'pwd';
                    $this->Execute();
                    $_SESSION['dir'] = $this->output;
                }else{
                    $this->directory = ROOT;
                    $this->ChangeDirectory();
                }
            }else{
                $this->directory = $_SESSION['dir'];
                $this->ChangeDirectory();
            }
        }

        public function Process(){
            $this->ParseCommand();
            $this->Execute();
	    	$parts = explode("\n",$this->output);
		    //$parts = array_slice($parts,1,-2);
		    //$this->output = implode("\n",$parts);
            return $this->output;
        }
        
        // Parse command for special functions, blocks
        
        public function ParseCommand(){
            
            // Explode command
            $command_parts = explode(" ",$this->command);
            
            // Handle 'cd' command
/*            if(in_array('cd',$command_parts)){
                $cd_key = array_search('cd', $command_parts);
                $cd_key++;
                $this->directory = $command_parts[$cd_key];
                $this->ChangeDirectory();
                // Remove from command
                $this->command = str_replace('cd '.$this->directory,'',$this->command);
            } */
            
            // Replace text editors with cat
            $editors = array('vi','vim','nano');
            $this->command = str_replace($editors,'cat',$this->command);
            
            // Handle blocked commands
            $blocked = explode(',',BLOCKED);
            if(in_array($command_parts[0],$blocked)){
                $this->command = 'echo ERROR: Command not allowed';
            }
            
            // Update exec command
            $this->command_exec = $this->command. ' 2>&1';
        }
        
        // Chnage Directory
        
        public function ChangeDirectory(){
            chdir($this->directory);
            // Store new directory
            $_SESSION['dir'] = shell_exec('pwd');
        }
        
        // Execute commands
        
        public function Execute(){

            if(function_exists('shell_exec')){
                $this->output = shell_exec($this->command_exec);
            }
           
            else{
                $this->output = 'Command execution not possible on this system';
            }
        }        
        
    }
    
?>