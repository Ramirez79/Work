<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

require_once(APPPATH . "controllers/common/rapi_utils.php");
require_once(APPPATH . "controllers/common/syslog_regex_rules.php");

class syslog extends abstract_controller
{
    private $timezone_offset = 0;
    private $syslog_regex_details = array();

    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
        $this->timezone_offset = date('Z');
        $this->SYSLOG_DIR = DEFAULT_NSLOG_DIR;
        $this->LOG_FILE_NAME = DEFAULT_NSLOG_FILE_NAME;
        $this->LOG_FILES_LIST = unserialize(SYSTEM_LOG_FILES);
        $this->count_syslog_messages = 0;
    }
    
    public function get_systemlog_files_inside_dir()
    {
        $response = array();
        $message_array = array();
        if(!$this->validate_session($response))
            return $response;

        $regex_array = array();
        for ($i = 0; $i < count($this->LOG_FILES_LIST); ++$i)
        {
            $regex_array[$i] = "/^(" . $this->LOG_FILES_LIST[$i] . "(.log)?(.appfw)?(.\d+)?(.gz)?)$/";
        }
        
        rapi_utils::populate_systemlog_file($message_array, $this->SYSLOG_DIR, "files", $this->LOG_FILES_LIST, $regex_array, "file_last_modified_time");

        $files_array = array();
        if(isset($message_array["files"]))
        {
            for ($j = 0; $j < count($this->LOG_FILES_LIST); ++$j)
                $files_array = array_merge($files_array, $this->process_systemlog_files($message_array["files"][$this->LOG_FILES_LIST[$j]]));
        }
        
        $response["systemlog_files_inside_dir"]["files"] = $files_array;
        $response["systemlog_files_inside_dir"]["log_directory"] = $this->SYSLOG_DIR;
        $response["systemlog_files_inside_dir"]["default_log_file"] = $this->LOG_FILE_NAME;
        return $response;
    }

    private function process_systemlog_files($syslog_files)
    {
        if(($count_syslog_files = count($syslog_files)) == 0)
            return;
        usort($syslog_files, array($this, "compare_systemlog_files"));
        foreach ($syslog_files as $key => $details) {
            foreach ($details as $name => $value) {
                $last_modified_time = datetime::createfromformat('D, d M Y H:i:s', $details["file_last_modified_time"]);
                $last_modified_year = $last_modified_time->format('Y');
                $_SESSION["file_last_modified_year"][$details["name"]] = $last_modified_year;
            }
        }

        return $syslog_files;
    }

    private function compare_syslog_files($a, $b)
    {
        return $a["end_system_time"] < $b["end_system_time"];
    }

    private function compare_systemlog_files($a, $b)
    {
        $firstDateString = $a["file_last_modified_time"];
        $firstDateTime = datetime::createfromformat('D, d M Y H:i:s', $firstDateString);
        $secondDateString = $b["file_last_modified_time"];
        $secondDateTime = datetime::createfromformat('D, d M Y H:i:s', $secondDateString);
        return $firstDateTime < $secondDateTime;
    }

    public function get_systemlog_messages($systemlog_messages, $filter)
    {
        $file_name = $filter["file_name"];
        $start = $filter["start"];
        $this->default_year = $_SESSION["file_last_modified_year"][$filter["file_name"]];
        
        $response = array();
        if(!$this->validate_session($response))
            return $response;

        $dir = $this->SYSLOG_DIR;
        $file_full_path = $dir . $file_name;
        $prev = -1;
        $next = -1;
    
        $file_content_type = "ns";
        for ($i = 0; $i < count($this->LOG_FILES_LIST); ++$i)
        {
            $filename_regex = "/^(" . $this->LOG_FILES_LIST[$i] . "(.log)?(.appfw)?(.\d+)?(.gz)?)$/";
            if (!preg_match($filename_regex, $file_name, $matches))
                continue;
            
            $file_content_type = $this->LOG_FILES_LIST[$i];
        }

        if(!is_file($file_full_path))
            $response["message"] = "File doesn't exist";
        else if(($syslog_messages = rapi_utils::get_file_contents($response, $file_full_path, $start, $prev, $next)) === false)
        {
            if (!array_key_exists("message", $response))
            {
                $response["message"] = "Error reading file.";
            }
        }
        else {
            $response["systemlog_messages"]["messages"] = $this->process_systemlog_messages($syslog_messages, $file_content_type);
            $response["systemlog_messages"]["prev"] = $prev;
            $response["systemlog_messages"]["next"] = $next;
            $response["systemlog_messages"]["count"] = $this->count_syslog_messages;
        }

        return $response;
    }

    private function process_systemlog_messages($syslog_messages, $file_content_type)
    {
        $this->count_syslog_messages = count($syslog_messages);
        if($this->count_syslog_messages == 0)
            return $syslog_messages;

        $syslog_rules = new syslog_regex_rules();
        $this->syslog_regex_details = $syslog_rules->get_regex_rules($file_content_type);
        
        $syslog_objects = array();
        for($i = $this->count_syslog_messages - 1; $i >= 0 ; $i--)
        {
            $syslog_object = $this->parse_systemlog_message($syslog_messages[$i]);
            $syslog_objects[] = $syslog_object;
        }
        return $syslog_objects;
    }
    
    //Following regex fields will be evaluated in order as long as match is not found
    //In the worst case the whole log line will be returned as "message" for each syslog object
    private function initialize_syslog_regex()
    {
        $generic_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<local\d+\.(.*?)>"),
            array("key" => "server_ip", "regex" => "(.*?)", "to_send" => false),
            array("key" => "gs_time", "regex" => "(\d\d\/\d\d\/\d\d\d\d:\d\d:\d\d:\d\d)\s[a-z]*", "to_send" => false), //GMT or system time based on configuration
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false), //get host name & use as regex
            array("key" => "ppe", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "module", "regex" => "(.*?)"),
            array("key" => "event_type", "regex" => "(.*?)"),
            array("key" => "event_id", "regex" => "(.*?)", "type" => "int"),
            array("key" => "zero", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $logfile_turnedover_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false), //get host name & use as regex
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $this->syslog_regex_details[] = array(
            "fields" => $generic_fields,
            "regex" => $this->get_syslog_regex($generic_fields)
        );
        $this->syslog_regex_details[] = array(
            "fields" => $logfile_turnedover_fields,
            "regex" => $this->get_syslog_regex($logfile_turnedover_fields)
        );
    }

    private function get_syslog_regex($fields)
    {
        $regex = "";
        foreach($fields as $field)
        {
            if($regex != "")
                $regex .= "\s";
            $regex .= $field["regex"];
        }
        return "/^" . $regex . "$/i";
    }

    private function parse_syslog_message($syslog_message)
    {
        $syslog_object = array();
        $success = false;
        foreach($this->syslog_regex_details as $syslog_regex_detail)
        {
            if($this->populate_syslog_object($syslog_object, $syslog_message, $syslog_regex_detail["fields"], $syslog_regex_detail["regex"]))
            {
                $success = true;
                break;
            }
        }
        if(!$success)
            $syslog_object["message"] = $syslog_message;
        return $syslog_object;
    }
    
    private function parse_systemlog_message($syslog_message)
    {
        $syslog_object = array();
        $success = false;
        foreach($this->syslog_regex_details as $syslog_regex_detail)
        {
            if($this->populate_systemlog_object($syslog_object, $syslog_message, $syslog_regex_detail["fields"], $syslog_regex_detail["regex"]))
            {
                $success = true;
                break;
            }
        }
        if(!$success)
            $syslog_object["message"] = $syslog_message;
        return $syslog_object;
    }

    private function populate_systemlog_object(&$syslog_object, $syslog_message, $fields, $regex)
    {
        if(!preg_match($regex, $syslog_message, $matches))
            return false;

        $current_year = $this->default_year;
        $count_matches = count($matches);
        $full_message = $matches[0];
        
        for($i = 1; $i < $count_matches; $i++)
        {
            $value = $matches[$i];
            $field = $fields[$i - 1];
            if($field["key"] == "current_year")
            {
                $current_year = $value;
                $this->default_year = $current_year;
                break;
            }
            else if($field["key"] == "gs_time")
            {
                $gs_year = DateTime::createFromFormat('m/d/Y:H:i:s', $value)->format('Y');
                $current_year = $gs_year;
                $this->default_year = $current_year;
                break;
            }
        }
        
        for($i = 1; $i < $count_matches; $i++)
        {
            $value = $matches[$i];
            $field = $fields[$i - 1];
            if(isset($field["to_send"]) && !$field["to_send"])
                continue;
            if(isset($field["uppercase"]) && $field["uppercase"])
                $value = strtoupper($value);
            if(isset($field["type"]))
            {
                if($field["type"] === "int")
                    $value = intval($value);
                else if($field["type"] === "time")
                {
                    $current_day = date('D', strtotime($value));
                    $timestring = 'd M ' . $current_year . ' H:i:s';
                    $timestamp = datetime::createfromformat('M d H:i:s', $value)->format($timestring);
                    $value = $current_day . ', ' . $timestamp;
                }
            }
            if($field["key"] == "meta_fields")
            {
                $meta_regex = "/(.*?)\\s*(\\S+)(?<!\\\\)=(.*)$/i";
                $key = null;
                $meta_str = $value;
                $meta_object = array();
                
                while(true)
                {
                    $match_value = preg_match($meta_regex, $meta_str, $meta_matches);
                    if(!$match_value)
                    {
                        if($key != null)
                        {
                            $meta_object[$key] = $meta_str;
                        }
                        break;
                    }
                    if($key != null)
                    {
                        $meta_object[$key] = $meta_matches[1];
                    }
                    $key = $meta_matches[2];
                    $meta_str = $meta_matches[3];
                }

                foreach($meta_object as $mkey => $mvalue)
                {
                    if($mkey == "src")
                        $syslog_object["client_ip"] = $mvalue;
                    elseif($mkey == "spt")
                        $syslog_object["source_port"] = $mvalue;
                    elseif($mkey == "method")
                        $syslog_object["method"] = $mvalue;
                    elseif($mkey == "request")
                        $syslog_object["request_url"] = $mvalue;
                    elseif($mkey == "msg")
                        $syslog_object["message"] = $mvalue;
                    elseif($mkey == "cn1")
                        $syslog_object["event_id"] = $mvalue;
                    elseif($mkey == "cn2")
                        $syslog_object["transaction_id"] = $mvalue;
                    elseif($mkey == "cs1")
                        $syslog_object["profile"] = $mvalue;
                    elseif($mkey == "cs2")
                        $syslog_object["cs2"] = $mvalue;
                    elseif($mkey == "cs3")
                        $syslog_object["session_id"] = $mvalue;
                    elseif($mkey == "cs4")
                        $syslog_object["severity_level"] = $mvalue;
                    elseif($mkey == "cs5")
                        $syslog_object["current_year"] = $mvalue;
                    elseif($mkey == "cs6")
                        $syslog_object["cs6"] = $mvalue;
                    elseif($mkey == "act")
                        $syslog_object["action"] = $mvalue;
                    elseif($mkey == "geolocation")
                        $syslog_object["geolocation"] = $mvalue;
                }
            }
            else
            $syslog_object[$field["key"]] = $value;
        }
        
        $full_message = substr($full_message, 16);
        if($full_message[0] === '<')
        {
            $pos = strpos($full_message, '>');
            if($pos !== false)
            {
                $full_message = substr($full_message, $pos + 2);
            }
        }
        $syslog_object["full_message"] = $full_message;
        return true;
    }
    
    private function populate_syslog_object(&$syslog_object, $syslog_message, $fields, $regex)
    {
        if(!preg_match($regex, $syslog_message, $matches))
            return false;

        $count_matches = count($matches);
        for($i = 1; $i < $count_matches; $i++)
        {
            $value = $matches[$i];
            $field = $fields[$i - 1];
            if(isset($field["to_send"]) && !$field["to_send"])
                continue;
            if(isset($field["type"]))
            {
                if($field["type"] == "int")
                    $value = intval($value);
                else if($field["type"] == "time")
                {
                    if(($temp_value = strtotime($value)) !== false)
                        $value = $temp_value + $this->timezone_offset;
                }
            }
            $syslog_object[$field["key"]] = $value;
        }
        return true;
    }
}
?>