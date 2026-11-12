<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class config_diff extends abstract_controller
{
    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
        $this->nsapi_token = $_SESSION["NSAPI"];
        require_once(APPPATH. "controllers/common/remote_shell.php");
    }

    public function get_config_diff($arg_list)
    {
        if(!isset($arg_list["base_file"]))
        {
            $base_file = "savedconfig";
        }
        else
        {
            $base_file =urldecode(trim($arg_list["base_file"]));

            // Validate base_file param
            if(($base_file != "savedconfig") && ($base_file != "runningconfig") && ($base_file != "ha_mode"))
            {
                // File path specified
                if(!file_exists($base_file))
                {
                    print("Invalid base_file");
                    return;
                }
            }
        }

        if(!isset($arg_list["second_file"]))
        {
            $second_file = "runningconfig";
        }
        else
        {
            $second_file = urldecode(trim($arg_list["second_file"]));

            // Validate second_file param
            if(($second_file != "savedconfig") && ($second_file != "runningconfig") && ($second_file != "ha_mode"))
            {
                // File path specified
                if(!file_exists($second_file))
                {
                    print("Invalid second_file");
                    return;
                }
            }
        }

        $cmd_to_execute  = "/netscaler/nsconfigaudit";
        if($second_file == "runningconfig" && $base_file == "savedconfig")
        {
            $cmd_to_execute .= " -sdiff";
        }
        else
        {
            if($base_file == "savedconfig")
                $base_file == "/nsconfig/ns.conf";
            if($base_file == "ha_mode")
                $cmd_to_execute = "nscli -s -U ::" .$_SESSION["NSAPI"] . " diff ns config runningconfig secondary:runningconfig";
            else if($second_file == "runningconfig")
                $cmd_to_execute .= " -diff " .  $base_file;
            else if($base_file == "runningconfig")
                $cmd_to_execute .= " -diff " .  $second_file;
            else
                $cmd_to_execute .= " -fdiff " . "-file1 " . escapeshellarg(addcslashes($base_file, '"')) . " -file2 " . escapeshellarg(addcslashes($second_file, '"'));
        }


        $all_partitions = $this->get_partition_data();
        if(sizeof($all_partitions) > 0)
        {
            $partiton_info = $this->get_default_partition($all_partitions);

            if(sizeof($partiton_info) > 0) // Partition present
            {
                $cmd_to_execute .= " -partitionid " . $partiton_info["partitionid"];
            }
        }

        require_once(APPPATH . "controllers/common/rapi_utils.php");

        if(($net_ssh = rapi_utils::get_ssh($ssh_response)) !== false)
        {
            $output = $net_ssh->exec('shell "'. $cmd_to_execute . '"');
        }
        else
        {
            $output = "";
        }

        // Removing the spaces and new lines as these cause problems across browsers when parsing the xml in front end. Refer xml childNodes children problems in IE and other browsers.
        $output = preg_replace('/\>\s+\</', '><', $output);

        // Removing Anything after the ending node
        $output = preg_replace('/(.*)\>.*$/s','${1}>',$output);

        // Removing Anything before the starting node
        $output = preg_replace('/^.*?\</s','<',$output);

        header(XML_HEADER);
        print(trim($output));
    }
}
?>
