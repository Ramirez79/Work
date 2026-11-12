<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class remotefilebrowser extends abstract_controller
{
    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
    }

    public function get_contents($arg_list)
    {
        if($this->check_permissions("shell", true) == 0)
        {
            return $this->do_filesystem_operation($arg_list);
        }
        else
        {
            return $this->do_nitro_operation($arg_list);
        }
    }

    private function do_nitro_operation($arg_list)
    {
        $request_method = $_SERVER["REQUEST_METHOD"];
        if($request_method == "GET")
        {
            return $this->get_filelist_from_nitro($arg_list);
        }
        else
        {
            $this->check_permissions("shell");
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $data = $data["object"];
            $data = json_decode( $data ,true);

            if(isset($data["params"]) && $data["params"]["action"] == "delete")
                return $this->delete($data["remotefiledir"]);
        } 
    }

    private function get_filelist_from_nitro($arg_list)
    {
        if($this->validate_arg_list($arg_list) === false)
        {
            $empty_response = array();
            return array("errorcode" => -1, "message" => "Failed to retrieve the folder contents");
        }
        $directory_name = trim($arg_list["path"]);    
        $file_items_info = array();

        if($this->execute_command('getsystemfile', array("filelocation" => $directory_name)) == 0)
        {
            $sslcertfiles = $this->get_model()->get_result();
            if($sslcertfiles["rc"] == 0 && count($sslcertfiles["List"]) > 0)
            {
                $file_items_info = $this->get_data_in_rapi_format($sslcertfiles, $file_items_info, $arg_list);
            }
        }
            else
            {
                return array("errorcode" => -1, "message" => "Failed to retrieve the folder contents");
            }
            
        return array("remotefiledir" => $file_items_info);
    }

    private function get_data_in_rapi_format($sslfiledata, $file_items_info, $arg_list)
    {
        foreach( $sslfiledata["List"] as $sslfile)
        {
            if(!isset($sslfile["filename"]))
                continue;

            $file_item_info = array(
                "is_dir" => false,
                "name" => $sslfile["filename"],
                "modified_dttm" => $sslfile["filemodifiedtime"],
                "accessed_dttm" => $sslfile["fileaccesstime"],
                "modified_timestamp" => "-NA-",
                "accessed_timestamp" => "-NA-",
                "file_size" => "-NA-"
            );   

            if(isset($arg_list["name"]))
            {
                if(!rapi_utils::does_filter_match($file_item_info, array("name" => $arg_list["name"]), false))
                    continue;
            }

            array_push($file_items_info, $file_item_info);
        }   

        return $file_items_info;
    }

    private function do_filesystem_operation($arg_list)
    {
        $request_method = $_SERVER["REQUEST_METHOD"];
        if($request_method == "GET")
        {
           return $this->get_dir_contents($arg_list);
        }
        else if($request_method == "POST")
        {
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $data = $data["object"];
            $data = json_decode( $data ,true);

            if(isset($data["params"]) && $data["params"]["action"] == "delete")
                return $this->delete($data["remotefiledir"]);
        }
    }

    private function delete($data)
    {
        $file = trim($data["name"]);
        $file = urldecode($file);
        $success = false;

        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
             if(is_dir($file))
                $success = $net_sftp->rmdir($file);
             else
                $success = $net_sftp->delete($file);
        }

        if($success === false)
        {
             $response["errorcode"] = 999;
             $response["message"] = (is_dir($file))?"Cannot delete Directory, It might contain some files":"Cannot delete the file";
             return $response;
        }

        $response["errorcode"] = 0;
        $response["message"] = "Done";
        return $response;
    }

    private function get_dir_contents($arg_list)
    {
        if($this->validate_arg_list($arg_list) === false)
        {
            $empty_response = array();
            return array("errorcode" => -1, "message" => "Failed to retrieve the folder contents");
        }
        $directory_name = trim($arg_list["path"]);
        $file_items_info = array();
        try
        {
            if ($handle = opendir($directory_name))
            {
                while (false !== ($entry = readdir($handle)))
                {
                    if ($entry != "." && $entry != "..")
                    {
                        $abs_file_path = $directory_name . $entry;
                        $is_dir = "false";
                        if(is_dir($abs_file_path)=== true)
                        {
                            $is_dir = "true";
                            if(isset($arg_list["is_file"])  && $arg_list["is_file"] === "true")
                            {
                                continue;
                            }
                        }

                        $modified_dttm = filemtime($abs_file_path);
                        $accessed_dttm = fileatime($abs_file_path);
                        $file_item_info = array(
                            "is_dir" => $is_dir,
                            "name" => $entry,
                            "modified_dttm" => $modified_dttm,
                            "accessed_dttm" => $accessed_dttm,
                            "modified_timestamp" => date ("F d Y H:i:s", $modified_dttm),
                            "accessed_timestamp" => date ("F d Y H:i:s", $accessed_dttm),
                            "file_size" => filesize($abs_file_path)
                        );

                        if(isset($arg_list["name"]))
                        {
                            if(!rapi_utils::does_filter_match($file_item_info, array("name" => $arg_list["name"]), false))
                                continue;
                        }

                        array_push($file_items_info, $file_item_info);
                    }
                }
            }
            else
            {
                return array("errorcode" => -1, "message" => "Failed to retrieve the folder contents");    
            }
            
            if(isset($handle))
                closedir($handle);
                
            return array("remotefiledir" => $file_items_info);
        }
        catch(Exception $e)
        {
            if(isset($handle))
                closedir($handle);
            return array("errorcode" => -1, "message" => "Failed to retrieve the folder contents");
        }
    }

    private function validate_arg_list($arg_list)
    {
        $rand = $this->get_headervalue("rand_key");
        $rand = isset($rand) ? $rand : $arg_list["rand"];

        if(!isset($rand) ||!isset($arg_list["path"]))
            return false;

        //rand is used to prevent CSRF attack
        if(!isset($_SESSION['rand']) || strcmp($rand, $_SESSION['rand']))
        {
                return false;
        }
        else
        {
            return true;
        }
    }

    private function get_headervalue($header_key)
    {
        $headers = apache_request_headers();
        $headers = array_change_key_case($headers, CASE_LOWER);
        $header_key = strtolower($header_key);

        if (isset($headers[$header_key]))
            return $headers[$header_key];

        return null;
    }
}
?>
