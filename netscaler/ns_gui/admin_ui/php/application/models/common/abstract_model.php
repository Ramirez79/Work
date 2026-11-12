<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

abstract class abstract_model extends CI_Model
{
    protected $server_ip = LOCAL_HOST;
    protected $result;

    protected function abstract_model()
    {
        parent::__construct();
    }

    protected function is_onboard_api()
    {
        return $this->server_ip == LOCAL_HOST;
    }

    public function execute_command($command, $parameters, $use_cache = false, $object_id = "", $use_api = true)
    {
        //If data is available in cache, return it
        if($use_cache)
        {
            if(isset($_SESSION[$object_id]))
            {
                $this->result = $_SESSION[$object_id];
                return $this->get_error_code();
            }
        }

        //Execute command
        $timestamp = time();
        $return_value = $this->command_execution($command, $parameters, $use_api);
        $this->result["timestamp"] = $timestamp;
        if($this->result["rc"] == NSERR_FEATDISABLED || $this->result["rc"] == NSERR_LICENSE || $this->result["rc"] == NSERR_AS_DEFAULT_PROFILE_IS_BYPASS)
            $return_value = 0;

        //Update cache
        if($use_cache)
            $_SESSION[$object_id] = $this->result;

        return $return_value;
    }

    abstract protected function command_execution($command, $parameters, $use_api = true);

    public function get_result()
    {
        return $this->result;
    }

    public function get_error_code()
    {
        $result = $this->get_result();
        return $result ? $result["rc"] : 0;
    }

    public function get_error_message()
    {
        $result = $this->get_result();
        return $result ? $result["message"] : "";
    }

    protected function set_error_code($rc = -1)
    {
        $this->result["rc"] = $rc;
    }

    protected function set_error_message($command, $reason = null)
    {
        $error_message = "$command command failed over API.";
        if($reason != null)
            $error_message .= " Reason: $reason";
        $this->result["message"] = $error_message;
    }
}
?>