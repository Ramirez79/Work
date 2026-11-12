<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//This controller acts as an adaptor to custom reports file
class custom_report_controller
{
    private static function create_new_xml()
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $doc->appendChild($doc->createElement('reports'));
        return $doc;
    }

    private static function load_and_validate_xml()
    {
        if(!file_exists(CUSTOM_REPORTS_FILE_PATH))
            return self::create_new_xml();

        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->load(CUSTOM_REPORTS_FILE_PATH);
        if(!$doc->schemaValidate(CUSTOM_REPORTS_SCHEMA_FILE_PATH))
        {
            //Backup the invalid xml file and create a new file
            exec("mv '" . CUSTOM_REPORTS_FILE_PATH . "' '" . CUSTOM_REPORTS_FILE_PATH . ".0'");
            return self::create_new_xml();
        }
        $doc->formatOutput = true;
        return $doc;
    }

    private static function save_xml($doc, $validate = true, $error_prefix = "", $print_on_success = true, $print_on_error = true, $reports_file_path=CUSTOM_REPORTS_FILE_PATH, $reports_temp_file_path=CUSTOM_REPORTS_TEMP_FILE_PATH)
    {
        if($validate && !$doc->schemaValidate(CUSTOM_REPORTS_SCHEMA_FILE_PATH))
        {
            if($print_on_error)
                self::print_error_message("INVALID_REQUEST", $error_prefix);
            return false;
        }

        //Try to write into temp file. If succeeds rename it to the original file. Else remove temp file.
        //This is to avoid erasing the old contents when file system is full.
        if($doc->save($reports_temp_file_path) == false)
        {
            exec("rm '" . $reports_temp_file_path . "'");
            if($print_on_error)
                self::print_error_message("ERROR_WRITING_REPORTS_FILE", $error_prefix);
            return false;
        }
        else
        {
            exec("mv '" . $reports_temp_file_path . "' '" . $reports_file_path . "'");
            if($print_on_success)
                print SUCCESS_RESULT;
            return true;
        }
    }

    private function print_error_message($error_message, $error_prefix = "", $print = true)
    {
        require_once(APPPATH."controllers/common/utils.php");
        $error = $error_prefix . utils::get_error_message($error_message);
        if($print)
            print $error;
        return $error;
    }

    public static function get_report($report_name, $reports = null)
    {
        if($reports == null)
        {
            $doc = self::load_and_validate_xml();
            $reports = $doc->getElementsByTagName('report');
        }
        foreach($reports as $report)
        {
            if($report->getAttribute('displayname') == $report_name)
                return $report;
        }
        return null;
    }

    private static function get_folder($folder_name, $folders = null)
    {
        if($folders == null)
        {
            $doc = self::load_and_validate_xml();
            $folders = $doc->getElementsByTagName('reports');
        }
        if($folder_name == CUSTOM_REPORTS)
            return $folders->item(0);
        foreach($folders as $folder)
        {
            if($folder->getAttribute('displayname') == $folder_name)
                return $folder;
        }
        return null;
    }

    public static function create_folder($data)
    {
        $folder_name = $data["displayname"];
        $parent_folder_name = $data["parent"];
        $error_prefix = "Error while creating folder [" . $folder_name . "].\n";

        $doc = self::load_and_validate_xml();
        $folders = $doc->getElementsByTagName('reports');
        if($folders->length > MAX_NO_OF_CUSTOM_FOLDERS)
        {
            self::print_error_message("MAX_CUSTOM_FOLDERS", $error_prefix);
            return;
        }
        if(self::get_folder($folder_name, $folders) != null)
        {
            self::print_error_message("FOLDER_ALREADY_EXISTS", $error_prefix);
            return;
        }
        $parent_folder = self::get_folder($parent_folder_name, $folders);
        if($parent_folder == null)
        {
            self::print_error_message("PARENT_FOLDER_DOESNT_EXIST", $error_prefix);
            return;
        }
        $folder = $doc->createElement('reports');
        $folder = $parent_folder->appendChild($folder);
        $folder->setAttribute('displayname', $folder_name);
        self::save_xml($doc);
    }

    public static function create_save_saveas_report($data)
    {
        $report_name = $data["displayname"];
        $parent_folder_name = $data["parent"];

        $doc = self::load_and_validate_xml();
        $reports = $doc->getElementsByTagName('report');
        $report = self::get_report($report_name, $reports);
        if($report != null) //Save request
            $report->nodeValue = '';    //clears the elements
        else //Create or save-as request
        {
            //Don't allow create or save-as, when max limit is already reached
            if($reports->length >= MAX_NO_OF_CUSTOM_REPORTS)
            {
                self::print_error_message("MAX_CUSTOM_REPORTS");
                return;
            }
            $parent_folder = self::get_folder($parent_folder_name, $doc->getElementsByTagName('reports'));
            if($parent_folder == null)
            {
                self::print_error_message("PARENT_FOLDER_DOESNT_EXIST");
                return;
            }
            $report = $doc->createElement('report');
            $report = $parent_folder->appendChild($report);
        }

        $report->setAttribute('displayname', $report_name);
        if(isset($data['starttime'], $data['endtime']))
        {
            $report->setAttribute('starttime', $data['starttime']);
            $report->setAttribute('endtime', $data['endtime']);
            $report->removeAttribute('last');
            $report->removeAttribute('unit');
        }
        if(isset($data['last'], $data['unit']))
        {
            $report->setAttribute('last', $data['last']);
            $report->setAttribute('unit', $data['unit']);
            $report->removeAttribute('starttime');
            $report->removeAttribute('endtime');
        }

        $separator = "||";
        $graphtypes = explode($separator, $data['graphtype']);
        $titles = explode($separator, $data['title']);
        $groups = explode($separator, $data['group']);
        $cores = explode($separator, $data['cores']);
        $ents = explode($separator, $data['ents']);
        $cntrs = explode($separator, $data['cntrs']);
        $props = isset($data["props"]) ? explode($separator, $data['props']) : array();
        for($i = 0 ; $i < $data['count']; $i++)
        {
            $element = $doc->createElement('element');
            $element = $report->appendChild($element);

            $element->setAttribute('graphtype', $graphtypes[$i]);
            $element->setAttribute('group', $groups[$i]);
            $element->setAttribute('cntrs', $cntrs[$i]);
            if(isset($ents[$i]) && $ents[$i] != "")
                $element->setAttribute('ents', $ents[$i]);
            if(isset($cores[$i]) && $cores[$i] != "")
                $element->setAttribute('cores', $cores[$i]);
            if(isset($titles[$i]) && $titles[$i] != "")
                $element->setAttribute('title', $titles[$i]);
            if(isset($props[$i]) && $props[$i] != "")
                $element->setAttribute("props", $props[$i]);
        }

        self::save_xml($doc);
    }

    public static function delete_report($data)
    {
        $error_prefix = "Error while deleting report [" . $data['displayname'] . "].\n";
        $doc = self::load_and_validate_xml();
        $reports = $doc->getElementsByTagName('report');
        $report = self::get_report($data['displayname'], $reports);
        if($report != null)
        {
            $report->parentNode->removeChild($report);
            self::save_xml($doc, false, $error_prefix);
            return;
        }
        self::print_error_message("ERROR_REPORT_NOT_FOUND", $error_prefix);
        return;
    }

    //Returns all the available report names
    public static function get_report_names()
    {
        $report_names = array();
        $doc = self::load_and_validate_xml();
        $root_node = $doc->getElementsByTagName('reports')->item(0);
        self::populate_children($root_node, $report_names);
        return $report_names;
    }

    private static function populate_children($node, &$report_names)
    {
        if(!$node || !$node->hasChildNodes())
            return;
        $child_nodes = $node->childNodes;
        for($i = 0; $i < $child_nodes->length; $i++)
        {
            $child_node = $child_nodes->item($i);
            if($child_node->nodeName != "report" && $child_node->nodeName != "reports")
                continue;
            $details = array("name"  =>  $child_node->getAttribute('displayname'));
            if($child_node->nodeName == "reports")
            {
                $details["nodes"] = array();
                self::populate_children($child_node, $details["nodes"]);
            }
            $report_names[] = $details;
        }
    }

    //Returns the default report name, if exists
    public static function get_default_report_name()
    {
        $doc = self::load_and_validate_xml();
        $reports = $doc->getElementsByTagName('report');
        foreach($reports as $report)
            return $report->getAttribute('displayname');
        return null;
    }

    //Converts a report in xml to php array format
    public static function convert_xml_report_to_array($name)
    {
        $doc = self::load_and_validate_xml();
        $reports = $doc->getElementsByTagName('report');
        foreach($reports as $report_node)
        {
            if($report_node->getAttribute('displayname') == $name)
            {
                $report = array('name'  =>  $name);
                $starttime = $report_node->getAttribute('starttime');
                $endtime = $report_node->getAttribute('endtime');
                $last = $report_node->getAttribute('last');
                $unit = $report_node->getAttribute('unit');
                if(!empty($starttime))
                    $report['stime'] = $starttime;
                if(!empty($endtime))
                    $report['etime'] = $endtime;
                if(!empty($last))
                    $report['last'] = $last;
                if(!empty($unit))
                    $report['unit'] = $unit;
                $report['elements'] = array();
                $elements = $report_node->getElementsByTagName('element');
                $i = 1;
                foreach($elements as $element)
                {
                    $report['elements'][$i] = array("id"    =>  $i);
                    $report['elements'][$i]['graphtype'] = $element->getAttribute('graphtype');
                    $report['elements'][$i]['func'] = $element->getAttribute('group');
                    $report['elements'][$i]['cntrs'] = $element->getAttribute('cntrs');
                    $ents = $element->getAttribute('ents');
                    if(!ns_empty($ents))
                        $report['elements'][$i]['ents'] = explode(",", $ents);
                    $cores = $element->getAttribute('cores');
                    if(!ns_empty($cores))
                    {
                        if($cores == ALL)
                            $report['elements'][$i]['cores'] = $cores;
                        else
                            $report['elements'][$i]['cores'] = explode(",", $cores);
                    }
                    $title = $element->getAttribute('title');
                    if(!ns_empty($title))
                        $report['elements'][$i]['title'] = $title;
                    //Convert "&amp;" to "&"
                    $props = htmlspecialchars_decode($element->getAttribute('props'));
                    if(!ns_empty($props))
                        $report['elements'][$i]['props'] = $props;
                    $i++;
                }
                return $report;
            }
        }
        return null;
    }

    public static function delete_reports_folders($data)
    {
        $display_names = $data['display_names'];
        $to_delete_count = count($display_names);
        $deleted_count = 0;
        $error_prefix = "Error occurred while deleting report(s)/folder(s).\n";

        $doc = self::load_and_validate_xml();
        $reports_nodes = $doc->getElementsByTagName("reports");
        $report_nodes = $doc->getElementsByTagName("report");
        foreach($display_names as $display_name)
        {
            if(self::is_folder($display_name))
            {
                if(($reports_node = self::get_folder($display_name, $reports_nodes)) != null)
                {
                    $reports_node->parentNode->removeChild($reports_node);
                    $deleted_count++;
                }
            }
            else if(($report_node = self::get_report($display_name, $report_nodes)) != null)
            {
                $report_node->parentNode->removeChild($report_node);
                $deleted_count++;
            }
        }
        if($deleted_count == $to_delete_count)
        {
            self::save_xml($doc, false, $error_prefix);
            return;
        }
        if($deleted_count > 0)
            self::save_xml($doc, false, $error_prefix, false);
        print $error_prefix . ($to_delete_count - $deleted_count) . " of the " . $to_delete_count . " selected reports/folders are not found";
    }

    public static function export_reports(&$data)
    {
        $display_names = $data["display_names"];
        $to_copy_count = count($display_names);
        $copied_count = 0;
        $error_prefix = "Error occurred while exporting report(s)/folder(s).\n";

        $new_doc = self::create_new_xml();
        $new_reports = $new_doc->getElementsByTagName("reports")->item(0);

        $doc = self::load_and_validate_xml();
        $reports_nodes = $doc->getElementsByTagName("reports");
        $report_nodes = $doc->getElementsByTagName("report");
        foreach($display_names as $display_name)
        {
            if(self::is_folder($display_name))
            {
                if(($reports_node = self::get_folder($display_name, $reports_nodes)) != null)
                {
                    $new_reports->appendChild($new_doc->importNode($reports_node, true));
                    $copied_count++;
                }
            }
            else if(($report_node = self::get_report($display_name, $report_nodes)) != null)
            {
                $new_reports->appendChild($new_doc->importNode($report_node, true));
                $copied_count++;
            }
        }
        if($copied_count < $to_copy_count)
            return $error_prefix . ($to_copy_count - $copied_count) . " of the " . $to_copy_count . " selected reports/folders are not found.\nPlease try again.";

        if(($output = $new_doc->saveXML()) === false)
            return $error_prefix . "Please try again.";

        $data["output"] = self::ns_gzencode($output);
        return SUCCESS_RESULT;
    }

    public static function import_reports()
    {
        $error_prefix = "Error occurred while importing report(s)/folder(s).\n";
        if(!isset($_FILES["import_reports_file"]))
            return self::print_error_message("IMPORT_REPORTS_INVAL_REQUEST", $error_prefix, false);

        $imported_file_details = $_FILES["import_reports_file"];
        if(!is_uploaded_file($imported_file_details["tmp_name"]))
        {
            switch($imported_file_details["error"])
            {
                case UPLOAD_ERR_INI_SIZE:
                    return self::print_error_message("IMPORT_REPORTS_ERR_INI_SIZE", $error_prefix, false);
                case UPLOAD_ERR_FORM_SIZE:
                    return self::print_error_message("IMPORT_REPORTS_ERR_FORM_SIZE", $error_prefix, false);
                case UPLOAD_ERR_PARTIAL:
                    return self::print_error_message("IMPORT_REPORTS_ERR_PARTIAL", $error_prefix, false);
                case UPLOAD_ERR_NO_FILE:
                    return self::print_error_message("IMPORT_REPORTS_ERR_NO_FILE", $error_prefix, false);
                case UPLOAD_ERR_NO_TMP_DIR:
                    return self::print_error_message("IMPORT_REPORTS_ERR_NO_TMP_DIR", $error_prefix, false);
                case UPLOAD_ERR_CANT_WRITE:
                    return self::print_error_message("IMPORT_REPORTS_ERR_CANT_WRITE", $error_prefix, false);
                case UPLOAD_ERR_EXTENSION:
                    return self::print_error_message("IMPORT_REPORTS_ERR_EXTENSION", $error_prefix, false);
                default:
                    return $error_prefix . "There was a problem with your upload(" . $imported_file_details["error"] . ").\nPlease try again.";
            }
        }

        $allowed_file_types = array("application/x-gzip-compressed",    //IE6, IE8
                                    "application/x-gzip",               //Firefox/Opera
                                    "application/gzip",                 //Chrome
                                    "application/octet-stream");        //Safari
        if(!in_array($imported_file_details["type"], $allowed_file_types))
            return self::print_error_message("IMPORT_REPORTS_ERR_EXTENSION", $error_prefix, false);
        if($imported_file_details["size"] == 0)
            return self::print_error_message("IMPORT_REPORTS_ERR_EMPTY_FILE", $error_prefix, false);

        $result;
        try
        {
            $result = self::ns_gzdecode($imported_file_details["tmp_name"]);
        }
        catch(Exception $e)
        {
            return self::print_error_message($e->getMessage(), $error_prefix, false);
        }

        libxml_use_internal_errors(true);
        $new_doc = strlen($result) > 0 ? DOMDocument::loadXML($result) : null;
        if(!$new_doc || !$new_doc->schemaValidate(CUSTOM_REPORTS_SCHEMA_FILE_PATH))
            return self::print_error_message("IMPORT_REPORTS_ERR_INVAL_FILE", $error_prefix, false);
        $new_doc->formatOutput = true;
        $old_doc = self::load_and_validate_xml();

        self::merge_nodes($old_doc->getElementsByTagName("reports")->item(0), $new_doc->getElementsByTagName("reports")->item(0), $old_doc);
        if(!self::save_xml($old_doc, false, $error_prefix, false, false))
            return self::print_error_message("ERROR_WRITING_REPORTS_FILE", $error_prefix, false);
        return SUCCESS_RESULT;
    }

    public function merge_nodes(&$old_node, $new_node, &$old_doc)
    {
        for($i = 0; $i < $new_node->childNodes->length; $i++)
        {
            $child_node = $new_node->childNodes->item($i);
            if($child_node->nodeName != "report" && $child_node->nodeName != "reports")
                continue;

            $display_name = $child_node->getAttribute("displayname");
            if(!self::is_valid_report_folder_name($display_name))
                continue;

            if($child_node->nodeName == "report")
            {
                $old_report_nodes = $old_doc->getElementsByTagName("report");
                if($old_report_nodes->length >= MAX_NO_OF_CUSTOM_REPORTS)
                    continue;

                if(self::get_report($display_name, $old_report_nodes) == null)
                    $old_node->appendChild($old_doc->importNode($child_node, true));
                else
                {
                    $suggested_display_name = $display_name . "_copy";
                    if(self::is_valid_report_folder_name($suggested_display_name) && (self::get_report($suggested_display_name, $old_report_nodes) == null))
                    {
                        $child_node->setAttribute("displayname", $suggested_display_name);
                        $old_node->appendChild($old_doc->importNode($child_node, true));
                    }
                }
            }
            else
            {
                $old_reports_nodes = $old_doc->getElementsByTagName("reports");
                if(($old_folder = self::get_folder($display_name, $old_reports_nodes)) == null)
                {
                    if($old_reports_nodes->length > MAX_NO_OF_CUSTOM_FOLDERS)
                        continue;

                    $old_folder = $old_doc->importNode($child_node, false);
                    $old_folder->setAttribute("displayname", $display_name);
                    $old_node->appendChild($old_folder);
                }
                self::merge_nodes($old_folder, $child_node, $old_doc);
            }
        }
        return;
    }

    private function is_valid_report_folder_name($name)
    {
        //Used as word_check in reporting.js also
        return preg_match("/^[\w \.:\-]{1," . MAX_NO_OF_CHARS_IN_REPORT_NAME . "}$/", $name);
    }

    private function is_folder(&$display_name)
    {
        //Used as FOLDER_IDENTIFIER in manage_custom_reports.js also
        $new_display_name = preg_replace("/\|\|$/", "", $display_name);
        $is_folder = ($new_display_name != $display_name);
        $display_name = $new_display_name;
        return $is_folder;
    }

    //In-memory gzip compression along with support for base64 encoding
    private function ns_gzencode($output, $base64_encode = true)
    {
        if($base64_encode)
            $output = base64_encode($output);
        return gzencode($output);
    }

    //File-based gzip decompression along with support for base64 decoding
    private function ns_gzdecode($file_name, $base64_decode = true)
    {
        $gz_file_handle = null;
        $exception = null;
        $uncompressed_data = "";
        try
        {
            if(($gz_file_handle = gzopen($file_name, "r")) === false)
                throw new Exception("IMPORT_REPORTS_ERR_FILE_OPEN");
            while(!gzeof($gz_file_handle))
                $uncompressed_data .= gzread($gz_file_handle, 4096);
            if($base64_decode && (($uncompressed_data = base64_decode($uncompressed_data)) === false))
                throw new Exception("IMPORT_REPORTS_ERR_FILE_DECODE");
        }
        catch(Exception $e)
        {
            $exception = $e;
        }
        if($gz_file_handle)
            gzclose($gz_file_handle);
        if($exception)
            throw $exception;
        return $uncompressed_data;
    }
}
?>