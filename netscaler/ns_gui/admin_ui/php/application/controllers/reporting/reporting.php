<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . "controllers/common/abstract_controller.php");

class reporting extends abstract_controller
{
    //Handles Reporting page request (loads top gauges, left tree and an iframe on the right side)
    public function main()
    {
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array(), array('name', 'new', 'ds', 'evtno', 'rno', 'outputformat', 'parent'));
        $this->validate_request_number($data);
        $this->validate_event_number($data);
        $ajax_request = $this->is_ajax_request($data);
        if($ajax_request)
        {
            if(!$this->start_session_for_ajax_request())
                return;
        }
        else if(!$this->start_session())
            return;
        
        if($ajax_request)
        {
            $this->retrieve_data_for_top_gauges($data);
            header(XML_HEADER);
            print "<messages>\n";
            print $this->load->view('common/gauges', $data, true);
            print "</messages>";
            return;
        }
        
        $this->load->helper('cookie');
        set_cookie("startupapp","rep", 60*60*24*30*12);
        
        $data['title'] = get_branding()->get_title() . ' - Reporting';

        if(!$this->pick_and_validate_data_source($data) || !$this->pick_and_validate_report($data))
            return;

        //Retrieve all custom/built-in/debug reports
        require_once("custom_report_controller.php");
        $data["custom_reports"] = custom_report_controller::get_report_names();

        $this->ensure_built_in_report_definitions($data);
        $data["built_in_reports"] = $data["built_in_report_definitions"]->get_built_in_reports();
        unset($data["built_in_report_definitions"]);

        if($data["internal"])
        {
            $this->ensure_debug_report_definitions($data);
            $data["debug_reports"] = $data["debug_report_definitions"]->get_debug_reports();
            unset($data["debug_report_definitions"]);
        }
        $data['rand_key'] = $_SESSION["rand"];
        $data['hide_top_gauges'] = !((isset($_COOKIE['showdb']) && $_COOKIE['showdb'] == "showdb") && is_default_data_source($data["ds"]));
        $this->retrieve_data_for_top_gauges($data);
        $this->check_is_secondary($data);
        $this->get_reporting_state($data);
        $this->load->view("reporting/reporting_view", $data);
    }

    //Retrieves data sources (using cache) & then picks/validates data source
    private function pick_and_validate_data_source(&$data, $store_data_sources = false)
    {
        $data_sources_result = $this->retrieve_data_sources();
        if(!$this->validate_result($data_sources_result, $data, PAGE_REQUEST, "data source"))
            return false;

        if(!isset($data["ds"]))
            $data["ds"] = $this->pick_data_source_name($data_sources_result);
        else
        {
            $data["ds"] = urldecode($data["ds"]);
            if(!$this->validate_data_source_name($data_sources_result, $data["ds"]))
                $this->show_error_page("INVALID_DS_NAME");
        }

        if($store_data_sources)
            $data['data_sources'] = $data_sources_result;

        $data["internal"] = $this->is_internal_data_source($data["ds"]);
        return true;
    }

    //If "default" data source is present, returns it
    //else returns the first available data source (a rare case)
    private function pick_data_source_name($data_sources_result)
    {
        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        $result = $rapi->main("is_clip");
        $default_data_source = (isset($result, $result["is_clip"]) && $result["is_clip"]) ? NS_RRD_CLUSTER_DS : NS_RRD_DEFAULT_DS;

        foreach($data_sources_result as $temp_ds)
        {
            if($temp_ds["name"] == $default_data_source)
                return $default_data_source;
        }
        return $data_sources_result[0]["name"];
    }

    //Makes sure passed data source name is present in the result
    private function validate_data_source_name($data_sources_result, $ds)
    {
        foreach($data_sources_result as $temp_ds)
        {
            if($temp_ds["name"] == $ds)
                return true;
        }
        return false;
    }

    //Picks report name & validates it by picking the report
    private function pick_and_validate_report(&$data, $pick_report = true)
    {
        if(!isset($data["name"]))
            $data["name"] = $this->pick_report_name($data["internal"]);
        else
            $data["name"] = urldecode($data["name"]);

        if($pick_report && !$this->pick_report($data))
        {
            abstract_controller::delete_cookie("drep");
            print '<script language="JavaScript" type="text/javascript">alert("Error : Invalid report access. Failed to load !"); parent.location.href = "/menu/rep";</script>';
        }

        return true;
    }

    //Order of preference is
    //1. Default report name stored in cookie
    //2. Default custom report name
    //3. Default debug/built-in report name
    private function pick_report_name($internal)
    {
        if(isset($_COOKIE["drep"]) && $_COOKIE["drep"] != "")
            return $_COOKIE["drep"];

        require_once("custom_report_controller.php");
        $report_name = custom_report_controller::get_default_report_name();
        if($report_name == null)
        {
            if($internal)
            {
                require_once("debug_report_definitions.php");
                $report_name = debug_report_definitions::get_default_report_name();
            }
            else
            {
                require_once("report_definitions.php");
                $report_name = report_definitions::get_default_report_name();
            }
        }
        $this->load->helper('cookie');
        set_cookie("drep", $report_name, 60*60*24*30*12, '', '/', '', false);
        $_COOKIE["drep"] = $report_name;
        return $report_name;
    }

    //If it is not a new custom report, picks the report by name (from debug/built-in/custom reports)
    private function pick_report(&$data)
    {
        $data["custom"] = false;
        $data["built_in"] = false;
        $data["debug"] = false;

        if(isset($data["new"]))
        {
            $this->ensure_built_in_report_definitions($data);
            $data["report"] = $data["built_in_report_definitions"]->get_default_report($data["name"], 1);
            $data["parent"] = isset($data["parent"])? urldecode($data["parent"]) : CUSTOM_REPORTS;
            $data["custom"] = true;
            return true;
        }

        if($data["internal"])
        {
            $this->ensure_debug_report_definitions($data);
            $data["report"] = $data["debug_report_definitions"]->get_report($data["name"]);
            if($data["report"] != null)
            {
                $data["debug"] = true;
                return true;
            }
        }

        $this->ensure_built_in_report_definitions($data);
        $data["report"] = $data["built_in_report_definitions"]->get_report($data["name"]);
        if($data["report"] != null)
        {
            $data["built_in"] = true;
            return true;
        }

        require_once("custom_report_controller.php");
        $data["report"] = custom_report_controller::convert_xml_report_to_array($data["name"]);
        if($data["report"] != null)
        {
            $data["custom"] = true;
            return true;
        }
        return false;
    }

    private function ensure_built_in_report_definitions(&$data)
    {
        if(!isset($data["built_in_report_definitions"]))
        {
            require_once("report_definitions.php");
            $data["built_in_report_definitions"] = new report_definitions();
        }
    }

    private function ensure_debug_report_definitions(&$data)
    {
        if(!isset($data["debug_report_definitions"]))
        {
            require_once("debug_report_definitions.php");
            $data["debug_report_definitions"] = new debug_report_definitions();
        }
    }

    //Handles Reporting content page request (toolbars, charts etc on the right side of tree)
    public function content()
    {
        if(!$this->start_session())
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array(), array("name", "ds", "new", "parent"));
        $data["title"] = get_branding()->get_title() . " - Reporting";
        $data["standalone"] = "YES";
        $data["no_footer_link"] = true;

        if(!$this->pick_and_validate_data_source($data, true) || !$this->pick_and_validate_report($data))
            return;

        require_once("_ns_stat_definitions.php");
        require_once(APPPATH."views/reporting/reporting_utils.php");

        //Retrieves groups if internal flag is turned on
        $data['groups'] = $this->retrieve_groups($data['ds'], $data['internal']);

        //Retrieve cores (UMPE)
        $data['cores'] = $this->retrieve_cores($data['ds']);

        //Retrieve entities
        $counters = array();
        $entities = array();
        $errors = array();
        $this->ensure_built_in_report_definitions($data);
        $functions = $data["built_in_report_definitions"]->get_report_functions($data['report']);
        $functions_cache = array();
        foreach($functions as $key=>$function)
        {
            $function = explode(";", $function);
            $function = $function[0];
            if(isset($functions_cache[$function]))
            {
                $errors[$key] = $functions_cache[$function]['errors'];
                $entities[$key] = $functions_cache[$function]['entities'];
                $counters[$key] = $functions_cache[$function]['counters'];
            }
            else
            {
                $result = $this->retrieve_entities($function, $data['ds']);
                $functions_cache[$function] = array();
                $functions_cache[$function]['errors'] = $result['errors'];
                $functions_cache[$function]['entities'] = $result['entities'];
                $errors[$key] = $result['errors'];
                $entities[$key] = $result['entities'];
                //Retrieve counters if internal flag is turned on
                $cntrs_result = $this->retrieve_counters($data['ds'], $function, $data['internal']);
                $functions_cache[$function]['counters'] = $cntrs_result;
                $counters[$key] = $cntrs_result;
            }
        }

        unset($functions_cache);
        unset($data["built_in_report_definitions"]);
        unset($data["debug_report_definitions"]);

        $data['entities'] = $entities;
        $data['errors'] = $errors;
        $data['counters'] = $counters;
        $data["is_tabular_view"] = $this->is_tabular_view();
        $data["is_default_data_source"] = is_default_data_source($data["ds"]);

        $this->load->view("reporting/reporting_content", $data);
    }

    //Handles counters table request
    public function counters_popup()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('img', 'func', 'ds'), array('title'));
        require_once("_ns_stat_definitions.php");
        require_once(APPPATH."views/common/view_utils.php");
        require_once(APPPATH."views/reporting/reporting_utils.php");
        if(isset($data['title']))
            $data['title'] = urldecode($data['title']);
        $internal = $this->is_internal_data_source($data['ds']);
        require_once(APPPATH."controllers/common/utils.php");
        if(!$internal && !utils::validate_function($data['func']))
            $this->show_error_page("INVALID_FUNC");

        //Retrieve cores (UMPE)
        $cores = $this->retrieve_cores($data['ds']);

        //Retrieve entities
        $temp_func = $data['func'];
        if($internal && !preg_match("/^stat/", $data['func']))
            $temp_func = "stat" . $data['func'];
        $result = $this->retrieve_entities($temp_func, $data['ds']);
        $error = $result['errors'];
        $entities = $result['entities'];

        //Retrieve counters if internal flag is turned on
        $groups_result = $this->retrieve_groups($data['ds'], $internal);
        $counters_result = $this->retrieve_counters($data['ds'], $temp_func, $internal);
        $avail_cntrs = $counters_result->get_counters();
        $sel_counters = array();

        reporting_utils::print_counters_combo_section($data['img'], $data['func'], $sel_counters, $entities, null, $error, $cores, null, $groups_result, $counters_result, $internal, isset($data['title']) ? $data['title'] : '');
    }

    //Returns the html for image and toolbar (to add a new report element in the page)
    public function image_toolbar()
    {
        if(!$this->start_session_for_ajax_request())
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('ds', 'id', 'numcpus'));
        require_once("_ns_stat_definitions.php");
        require_once("report_definitions.php");
        require_once(APPPATH."views/common/view_utils.php");
        require_once(APPPATH."views/reporting/reporting_utils.php");
        $report_definitions = new report_definitions();
        $report = $report_definitions->get_default_report("NA", $data['id']);
        $stime_default_value = report_definitions::get_report_stime($report);
        $etime_default_value = report_definitions::get_report_etime($report);
        $last_default_value = report_definitions::get_report_last($report);
        $unit_default_value = report_definitions::get_report_unit($report);
        $report_func = $report['elements'][0]['func'];

        $internal = $this->is_internal_data_source($data['ds']);
        $cores = $this->retrieve_cores($data['ds']);
        //Retrieve counters if internal flag is turned on
        $counters = array("");
        $groups = $this->retrieve_groups($data['ds'], $internal);
        $counters[$data['id']] = $this->retrieve_counters($data['ds'], $report_func, $internal);
        $sel_counters = reporting_utils::create_counters_objects(reporting_utils::get_cntrs_for_chart($report['elements'][0], $cores, $data['ds'], $data['numcpus']), $report_func, $internal, $counters[$data['id']]);

        reporting_utils::print_image_toolbar($data['id'], $report_func, $sel_counters, array(), null, null, $cores, null, $groups, $counters, $internal, '', $this->is_tabular_view());
    }

    //Saves/Saves-as report
    public function save_report()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array('displayname', 'parent', 'count', 'graphtype', 'group', 'cores', 'ents', 'cntrs'), array('starttime', 'endtime', 'last', 'unit', 'title', 'props'));
        $data['displayname'] = urldecode($data['displayname']);
        $data['parent'] = urldecode($data['parent']);
        if(isset($data["props"]))
            $data["props"] = urldecode($data["props"]);
        require_once("custom_report_controller.php");
        custom_report_controller::create_save_saveas_report($data);
    }

    //Deletes report
    public function delete_report()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array('displayname'));
        $data['displayname'] = urldecode($data['displayname']);
        require_once("custom_report_controller.php");
        custom_report_controller::delete_report($data);
    }

    //Deletes reports
    public function delete_reports_folders()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array('display_names'));
        $data['display_names'] = explode(",", urldecode($data['display_names']));
        require_once("custom_report_controller.php");
        custom_report_controller::delete_reports_folders($data);
    }

    //Checks whether a report with the same name exists or not
    public function check_report_exists()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array('displayname'));
        require_once("custom_report_controller.php");
        if(custom_report_controller::get_report(urldecode($data['displayname'])) == null)
            print SUCCESS_RESULT;
        else
            print "1";
    }

    //Creates folder
    public function create_folder()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array('displayname', 'parent'));
        $data['displayname'] = urldecode($data['displayname']);
        $data['parent'] = urldecode($data['parent']);
        require_once("custom_report_controller.php");
        custom_report_controller::create_folder($data);
    }

    //Retrieves cores
    private function retrieve_cores($ds)
    {
        $cores_parameters = array(
            'datasource'   => $ds,
        );
        $this->execute_command("getsystemcore", $cores_parameters, true, "REP_DS_" . $ds, false);
        $cores_result = $this->get_model()->get_result();
        $cores = null;
        if($cores_result['rc'] == 0 && isset($cores_result['List'][0]['response']))
        {
            $cores = preg_replace("/\\\"/", "", $cores_result['List'][0]['response']);
            //Temporary check - once back end is fixed, this should be removed
            $cores = preg_replace("/,$/", "", $cores);
        }
        return $cores;
    }

    //Retrieves entities
    private function retrieve_entities($function, $ds)
    {
        $return_value = array(
            'entities'  =>  null,
            'errors'    =>  null,
        );
        if(view_utils::is_an_entity_based_function($function))   //In case of entity based groups, fetch entities
        {
            $parameters = array(
                'type'          =>  preg_replace("/^stat/", "", $function),
                'datasource'    =>  $ds,
            );
            $this->execute_command("getsystementity", $parameters, false, '', false);
            $result = $this->get_model()->get_result();
            if($result['rc'] != 0)
            {
                $returncode = $result['rc'];
                $message = $result['message'];
                $return_value['errors'] = "Error retrieving entities.<br>Return code = $returncode.<br>Error message = $message.<br>";
            }
            else
            {
                $result = isset($result['List'][0]['response']) ? $result['List'][0]['response'] : "";
                $return_value['entities'] = preg_replace("/\\\"/", "", $result);
                $return_value['entities'] = preg_replace("/,$/", "", $return_value['entities']);
                if(!ns_empty($return_value['entities']))
                {
                    $entities = explode(",", $return_value['entities']);
                    $entities_arr = array();
                    foreach($entities as $entity)
                    {
                        $state = substr(strrchr($entity, " "), 1);
                        $name = substr($entity, 0, strpos($entity, " " . $state));
                        $entities_arr[] = array("name" => $name, "state" => $state);
                    }
                    usort($entities_arr, array("reporting", "name_cmp"));
                    $return_value['entities'] = $entities_arr;
                }
            }
        }
        return $return_value;
    }

    //Retrieves data sources
    private function retrieve_data_sources($clear_cache = false)
    {
        if($clear_cache)
            $this->remove_cached_object("REP_DATA_SOURCES");
        $this->execute_command("getsystemdatasource", '', true, "REP_DATA_SOURCES", false);
        $data_sources_result =  $this->get_model()->get_result();
        //Don't cache invalid and empty results
        if($data_sources_result['rc'] != 0 || !isset($data_sources_result['List'][0]['response']))
            $this->remove_cached_object("REP_DATA_SOURCES");
        else if($data_sources_result['rc'] == 0 && isset($data_sources_result['List'][0]['response']))
        {
            //Remove double quotes & comma at the end
            $data_sources_result['List'][0]['response'] = preg_replace("/\\\"/", "", $data_sources_result['List'][0]['response']);
            $data_sources_result['List'][0]['response'] = preg_replace("/,$/", "", $data_sources_result['List'][0]['response']);
            if(strlen($data_sources_result['List'][0]['response']) <= 0)
            {
                unset($data_sources_result['List'][0]['response']);
                $this->remove_cached_object("REP_DATA_SOURCES");
            }
            else
            {
                $data_sources = explode(",", $data_sources_result['List'][0]['response']);
                $data_sources_arr = array();
                foreach($data_sources as $data_source)
                {
                    list($name, $end_time, $numcpus) = explode(" ", $data_source);
                    require_once(APPPATH."views/common/view_utils.php");
                    $end_time = ($end_time == "0") ? "-NA-" : view_utils::convert_local_to_client_time($end_time);
                    $data_sources_arr[] = array("name" => $name, "end_time" => $end_time, "numcpus" => $numcpus);
                }
                usort($data_sources_arr, array("reporting", "name_cmp"));
                $data_sources_result['List'][0]['response'] = $data_sources_arr;
            }
        }
        return $data_sources_result;
    }

    //Fetches the latest data sources by clearing the cache (refresh)
    public function refresh_data_sources()
    {
        if(!$this->start_session_for_ajax_request())
            return;
        $data_sources_result = $this->retrieve_data_sources(true);
        $data_sources = "";
        if($this->validate_result($data_sources_result, null, AJAX_REQUEST, "data source"))
        {
            foreach($data_sources_result as $data_source)
                $data_sources .= $data_source["name"] . "/" . $data_source["end_time"] . ",";
            $data_sources = preg_replace("/,$/", "", $data_sources);
        }
        print $data_sources;
    }

    //Retrieves groups for internal counters
    private function retrieve_groups($ds, $internal)
    {
        $counter_groups = array();
        $return_code = null;
        $return_message = null;
        if(!$internal)
        {
            $_ns_stat_definitions = new _ns_stat_definitions();
            $feature = $_ns_stat_definitions->feature;
            $stats_group = view_utils::get_reporting_group_array(true);
            foreach($stats_group as $name=>$descr)
            {
                $cmd = $_ns_stat_definitions->get_command($name);
                $license = null;
                if(isset($cmd['license']))
                {
                    $license = array();
                    foreach($cmd['license'] as $lic)
                        $license[] = strtolower($feature[$lic]);
                }
                $counter_groups[] = new counter_group($name, $descr, $license);
            }
        }
        else
        {
            $parameters = array(
                'datasource'    =>  $ds
            );
            $this->execute_command('getsystemcountergroup', $parameters, false, '', false);
            $result = $this->get_model()->get_result();
            if($result['rc'] != 0)
            {
                $return_code = $result['rc'];
                $return_message = $result['message'];
            }
            $result = isset($result['List'][0]['response']) ? $result['List'][0]['response'] : "";
            $result = preg_replace("/\\\"/", "", $result);
            $result = explode(",", preg_replace("/,$/", "", $result));
            usort($result, "strcasecmp");
            $counter_groups = array(new counter_group(ALL_GROUP, ALL_GROUP, null));
            foreach($result as $name)
                $counter_groups[] = new counter_group($name, $name, null);
        }
        return new counter_groups_result($counter_groups, $return_code, $return_message);
    }

    //Retrieves internal counters
    private function retrieve_counters($ds, $group, $internal, $all_in_json_format = false)
    {
        $counters = array();
        $counters_for_json_response = array();
        $return_code = null;
        $return_message = null;
        if(!$internal)
        {
            $_ns_stat_definitions = new _ns_stat_definitions();
            $nsstat_cmd = $_ns_stat_definitions->get_command($group);
            if(!$nsstat_cmd)
                return new counters_result($counters, $return_code, $return_message);
            $nsstat_cntrs = $nsstat_cmd['cntrs'];
            foreach($nsstat_cntrs as $counter_name=>$counter)
            {
                if(!view_utils::can_counter_be_shown($counter, $group))
                    continue;
                $counter_desc = view_utils::get_counter_descr($counter, $nsstat_cntrs, true);
                $counter_tooltip = isset($counter['tooltip']) ? $counter['tooltip'] : $counter_desc;
                if(!view_utils::is_rate_counter($counter_name))
                    $counter_name = preg_replace("/rate/", "Rate", $counter_name);
                $counters[] = new counter($counter_name, $counter_desc, $counter_tooltip, $group);
            }
        }
        else
        {
            $group_parameter = preg_replace("/^stat/", "", $group);
            if($all_in_json_format || view_utils::is_an_entity_based_function("stat" . $group_parameter))
            {
                $parameters = array('datasource'    =>  $ds);
                if($group_parameter != ALL_GROUP)
                    $parameters['countergroup'] = $group_parameter;
                $this->execute_command('getsystemcounters', $parameters, false, '', false);
                $result = $this->get_model()->get_result();
                if($result['rc'] != 0)
                {
                    $return_code = $result['rc'];
                    $return_message = $result['message'];
                }
                $result = isset($result['List'][0]['response']) ? $result['List'][0]['response'] : "";
                $result = preg_replace("/\\\"/", "", $result);
                $result = explode(",", preg_replace("/,$/", "", $result));
                usort($result, array("reporting", "counter_name_cmp"));
                foreach($result as $counter_name)
                {
                    if($counter_name == "")
                        continue;
                    if(strpos($counter_name, ":") === false)
                    {
                        $cntr_codename = $counter_name;
                        $cntr_varname = $counter_name;
                    }
                    else
                        list($cntr_codename, $cntr_varname) = explode(":", $counter_name);
                    if($all_in_json_format)
                        $counters_for_json_response[] = array($cntr_codename, $cntr_varname);
                    else
                        $counters[] = new counter($cntr_codename, $cntr_varname, $cntr_codename, preg_replace("/^stat/", "", $group));
                }
                if($all_in_json_format)
                    return json_encode($counters_for_json_response);
            }
        }
        return new counters_result($counters, $return_code, $return_message);
    }

    //Retrieves all counters in json format (used in counters popup for internal data sources).
    public function retrieve_all_counters()
    {
        if(!$this->start_session_for_ajax_request())
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array("ds"));
        if(!($internal = $this->is_internal_data_source($data["ds"])))
            return;
        print $this->retrieve_counters($data["ds"], ALL_GROUP, $internal, true);
    }

    //Handles historical view chart request
    public function chart()
    {
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('func', 'id', 'type', 'ds', 'cntrs'), array('last', 'unit', 'stime', 'etime', 'ents', 'cores', 'title', 'rand', 'outputformat', 'filetype', 'width', 'props'));
        if($data["file_request"] = $this->is_file_request($data))
        {
            if(!isset($data["filetype"]))
                $data["filetype"] = "csv";
            if(!$this->start_session_for_file_request(false, $data["filetype"]))
                return;
        }
        else if(!$this->start_session_for_image_request())
            return;
        require_once("_ns_stat_definitions.php");
        require_once(APPPATH."views/common/view_utils.php");
        if(isset($data['title']))
            $data['title'] = urldecode($data['title']);
        if(isset($data["props"]))
            $data["props"] = urldecode($data["props"]);
        $data['internal'] = $this->is_internal_data_source($data['ds']);
        //if($data['internal'] && !preg_match("/^stat/", $data['func']))
            //$data['func'] = "stat" . $data['func'];
        $new_func = explode(";", $data['func']);
        if(!$data['internal'])
        {
            require_once(APPPATH."controllers/common/utils.php");
            foreach($new_func as $f)
            {
                if(!utils::validate_function($f))
                {
                     $data['error_message'] = utils::get_error_message("INVALID_FUNC");
                     print $this->load->view("reporting/chart", $data, true);
                     return;
                }
            }
        }
        $data['ents'] = isset($data['ents']) ? urldecode(preg_replace("/%252F/", "/", $data['ents'])) : "";
        $parameters = array(
            'counters'      =>  preg_replace("/;/", ",", $data['cntrs']),
            'datasource'    =>  $data['ds']
        );
        if(isset($data['last']))
        {
            $_SESSION['rep_last'] = $data['last'];
            $parameters['last'] = $data['last'];
        }
        if(isset($data['unit']))
        {
            $_SESSION['rep_unit'] = $data['unit'];
            $parameters['unit'] = $data['unit'];
        }
        if(isset($data['last']) || isset($data['unit']))
        {
            if(isset($_SESSION['rep_stime']))
                unset($_SESSION['rep_stime']);
            if(isset($_SESSION['rep_etime']))
                unset($_SESSION['rep_etime']);
        }
        if(isset($data['stime']))
        {
            $_SESSION['rep_stime'] = urldecode($data['stime']);
            $data['stime'] = view_utils::convert_client_to_local_time($_SESSION['rep_stime']);
            $parameters['startTime'] = preg_replace("/\-|:| /", "", $data['stime']);
        }
        if(isset($data['etime']))
        {
            $_SESSION['rep_etime'] = urldecode($data['etime']);
            $data['etime'] = view_utils::convert_client_to_local_time(urldecode($_SESSION['rep_etime']));
            $parameters['endTime'] = preg_replace("/\-|:| /", "", $data['etime']);
        }
        if(isset($data['stime']) || isset($data['etime']))
        {
            if(isset($_SESSION['rep_last']))
                unset($_SESSION['rep_last']);
            if(isset($_SESSION['rep_unit']))
                unset($_SESSION['rep_unit']);
        }
        if(!$data['internal'])
            $data['cntrs'] = preg_replace("/Rate/", "rate", $data['cntrs']);
        $temp_cntrs = explode(";", $data['cntrs']);
        $func_map = array();
        $i = 0;
        if(count($new_func) != count($temp_cntrs))
            $this->show_404();
        foreach($temp_cntrs as $cntr_names)
        {
            $func_map[$new_func[$i++]] = explode(",", $cntr_names);
        }
        $data['orig_cntrs'] = explode(",", preg_replace("/;/", ",", $data['cntrs']));
        $data['cntrs'] = $func_map;

        /* Currently API accpets 'n' counters but 1 entity and/or 1 core. If user selects multiple entities/cores,
         * we make the required number of api calls. Result of a command would look like,
         * "time0 cntr0-val cntr1-val,time1 cntr1-val cntr2-val, time2 cntr1-val cntr2-val". For multiple cores/entities,
         * we get similar result multiple times and we parse accordingly.
         */
        if(isset($data['cores']))
        {
            if(ns_empty($data['ents'])) //Not an entity based group
            {
                $cores = explode(",", $data['cores']);
                $command = "getsystemglobaldata";
                $i = 0;
                $result = array();
                foreach($cores as $core)
                {
                    $parameters['core'] = $core;
                    $this->execute_command($command, $parameters, false, '', false);
                    $result[$i] = $this->get_model()->get_result();
                    if(!$this->validate_result($result[$i], $data, $this->current_request_type))
                        return;
                    $i++;
                }
                $data['result'] = $result;
            }
            else
            {
                $cores = explode(",", $data['cores']);
                $entities = explode(",", $data['ents']);
                $command = "getsystementitydata";
                //Form parameters considering positional arguments for exe
                $parameters = array_merge(array ('type' => preg_replace("/^stat/", "", $data['func']),
                                                 'name' => null, //dummy will be replaced later
                                                 ), $parameters);
                $i = 0;
                $result = array();
                foreach($cores as $core)
                {
                    $parameters['core'] = $core;
                    foreach($entities as $entity)
                    {
                        $parameters['name'] = $entity;
                        $this->execute_command($command, $parameters, false, '', false);
                        $result[$i] = $this->get_model()->get_result();
                        if(!$this->validate_result($result[$i], $data, $this->current_request_type))
                            return;
                        $i++;
                    }
                }
                $data['result'] = $result;
            }
        }
        else if(ns_empty($data['ents'])) //Not an entity based group
        {
            $command = "getsystemglobaldata";
            $this->execute_command($command, $parameters, false, '', false);
            $data['result'] = $this->get_model()->get_result();
            if(!$this->validate_result($data['result'], $data, $this->current_request_type))
                return;
        }
        else //In case of entities, fire command for each entity
        {
            //Form parameters considering positional arguments for exe
            $parameters = array_merge(array ('type' => preg_replace("/^stat/", "", $data['func']),
                                             'name' => null, //dummy will be replaced later
                                            ), $parameters);
            $entities = explode(",", $data['ents']);
            $command = "getsystementitydata";
            $i = 0;
            $result = array();
            foreach($entities as $entity)
            {
                $parameters['name'] = $entity;
                $this->execute_command($command, $parameters, false, '', false);
                $result[$i] = $this->get_model()->get_result();
                if(!$this->validate_result($result[$i], $data, $this->current_request_type))
                    return;
                $i++;
            }
            $data['result'] = $result;
        }
        $data['func'] = $new_func;
        //Retrieve events for non default data source, only for image requests (not for file request - export option)
        //and if hide event cookie is not there
        if( !is_default_data_source($data["ds"]) &&
            $this->current_request_type == IMAGE_REQUEST &&
            !(isset($_COOKIE['hideevt']) && $_COOKIE['hideevt'] == "hideevt"))
            $this->populate_events($data, $parameters);
        $this->load->view("reporting/chart", $data);
    }

    //Validates chart result and prints error charts
    private function validate_result(&$result, $data, $request, $disp_name="data")
    {
        if($result['rc'] != 0)
        {
            require_once(APPPATH."views/common/view_utils.php");
            if($request == AJAX_REQUEST)
                view_utils::print_error_result_xml($result, $disp_name);
            else if($request == PAGE_REQUEST)
            {
                print $this->load->view("common/header", $data, true);
                view_utils::print_error_result_page($result, $disp_name);
                print $this->load->view("common/footer", $data, true);
            }
            else if($request == IMAGE_REQUEST)
            {
                $data['error_message'] = view_utils::get_error_string($result, $request, $disp_name);
                print $this->load->view("reporting/chart", $data, true);
            }
            else if($request == FILE_REQUEST)
                $this->print_error_for_file_request(view_utils::get_error_string($result, $request, $disp_name));
            return false;
        }
        if(!isset($result['List']) || count($result['List']) == 0 || !isset($result['List'][0]['response']))
        {
            if($request == PAGE_REQUEST)
            {
                print $this->load->view("common/header", $data, true);
                require_once(APPPATH."views/common/view_utils.php");
                view_utils::print_error_string("No $disp_name available");
                print $this->load->view("common/footer", $data, true);
            }
            else if($request == IMAGE_REQUEST)
            {
                $data['error_message'] = "No $disp_name available";
                print $this->load->view("reporting/chart", $data, true);
            }
            else if($request == FILE_REQUEST)
                $this->print_error_for_file_request("No $disp_name available");
            return false;
        }
        if(is_string($result['List'][0]['response']))
            $result = preg_replace("/\\\"/", "", $result['List'][0]['response']);
        else
            $result = $result['List'][0]['response'];
        return true;
    }

    //Returns the image map stored in session. Also used by keepAlivePHPSession() in utils.js.
    public function image_map()
    {
        if(!$this->start_session_for_ajax_request())
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('id'));
        if(isset($_SESSION[$data['id'] . '_map']))
        {
            print($_SESSION[$data['id'] . '_map']);
            unset($_SESSION[$data['id'] . '_map']);
        }
        else if($data['id'] == "rest_response")
        {
            $response["errorcode"] = 0;
            $response["message"] = "Done";
            
            header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
            header("Pragma: no-cache");
            header(JSON_HEADER);
            ob_start("ob_gzhandler");
            print json_encode($response);
        
        }
        else
            print "";
    }

    function name_cmp($a, $b)
    {
        return strcasecmp($a["name"], $b["name"]);
    }

    function counter_name_cmp($a, $b)
    {
        if(strpos($a, ":") !== false)
            list($cntr_codename, $a) = explode(":", $a);
        if(strpos($b, ":") !== false)
            list($cntr_codename, $b) = explode(":", $b);
        return strcasecmp($a, $b);
    }

    public function manage_custom_reports()
    {
        if(!$this->start_session())
            return;
        require_once("custom_report_controller.php");
        $data = array(  "report_names"  =>  custom_report_controller::get_report_names(),
                        "standalone"    =>  "YES",
                        "no_footer_link"        =>  true,
                        "no_loading_content"    =>  true,
                        "title"         =>  get_branding()->get_title() . " - Manage Custom Reports");
        $this->check_is_secondary($data);
        $this->load->view("reporting/manage_custom_reports", $data);
    }

    //Retrieves events to be plotted on reporting charts
    private function populate_events(&$data, $parameters)
    {
        $new_parameters = array("datasource" => $parameters["datasource"]);
        if(isset($parameters["last"]))
            $new_parameters["last"] = $parameters["last"];
        if(isset($parameters["unit"]))
            $new_parameters["unit"] = $parameters["unit"];
        if(isset($parameters["startTime"]))
            $new_parameters["startTime"] = $parameters["startTime"];
        if(isset($parameters["endTime"]))
            $new_parameters["endTime"] = $parameters["endTime"];
        $this->execute_command("getsystemeventhistory", $new_parameters, false, '', false);
        $result = $this->get_model()->get_result();
        if($result['rc'] != 0 || !isset($result['List']) || count($result['List']) == 0 || !isset($result['List'][0]['response']))
            return;
        $data["events"] = is_string($result['List'][0]['response']) ? preg_replace("/\\\"/", "", $result['List'][0]['response']) : $result['List'][0]['response'];
    }

    //Determines a data source is internal or not (used in showing internal counters/reports)
    //Similar function is defined in reporting.js
    private function is_internal_data_source($datasource_name)
    {
        return (preg_match("/^__/", $datasource_name));
    }

    //Determines the default view (tabular or graphical)
    //Similar function is defined in reporting.js
    private function is_tabular_view()
    {
        return (isset($_COOKIE['trep']) && $_COOKIE['trep'] == 'trep');
    }

    //Similar function is defined in reporting.js
    public static function get_properties_key_map()
    {
        return array(
                        //Chart properties
                        "bg_color"          =>  "bgclr",
                        "plot_bg_color"     =>  "pbgclr",
                        "plot_edge_color"   =>  "peclr",
                        "h_grid_color"      =>  "hgclr",
                        "v_grid_color"      =>  "vgclr",
                        "h_grid_width"      =>  "hgwdh",
                        "v_grid_width"      =>  "vgwdh",
                        "text_font_color"   =>  "tfclr",
                        "scale"             =>  "scl",
                        "scale2"            =>  "scl2",
                        "y_axis2"           =>  "y2",
                        "multiple_axes"     =>  "maxs",
                        //Data set properties
                        "color"             =>  "clr",
                        "plot_type"         =>  "ptyp",
                        "3d"                =>  "3d",
                        "width"             =>  "wdh",
                        "effect"            =>  "efc",
                        "shape"             =>  "shp"
                    );
    }

    //Similar function is defined in reporting.js
    public static function get_properties_key_reverse_map()
    {
        return array_flip(self::get_properties_key_map());
    }

    public static function get_global_conf()
    {
        if(file_exists(REPORTS_CONF_FILE_PATH) && (fileperms(REPORTS_CONF_FILE_PATH) & 0x0004) == 0x0004)
            return parse_ini_file(REPORTS_CONF_FILE_PATH);
        return null;
    }

    public static function write_global_conf($global_conf)
    {
        $conf_content = "";
        foreach($global_conf as $key => $value)
            $conf_content .= $key . " = " . $value . "\r\n";
        $error = true;
        if($file_handle = fopen(REPORTS_CONF_FILE_PATH, 'w'))
        {
            if(fwrite($file_handle, $conf_content) !== false)
                $error = false;
            fclose($file_handle);
        }
        if($error)
        {
            require_once(APPPATH."controllers/common/utils.php");
            print utils::get_error_message("ERROR_WRITING_REPORTS_CONF_FILE");
        }
        else
            print SUCCESS_RESULT;
    }

    public function apply_global_conf()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array('props'));
        $data["props"] = urldecode($data["props"]);
        if(!input_validator::validate("props", $data["props"]))
        {
            require_once(APPPATH."controllers/common/utils.php");
            print utils::get_error_message("INVALID_REQUEST");
            return;
        }
        $data["props"] = explode("&", $data["props"]);

        $global_conf = self::get_global_conf();
        if(ns_empty($global_conf))
            $global_conf = array();
        $key_map = self::get_properties_key_map();

        foreach($data["props"] as $property)
        {
            if(strpos($property, "=") === false)
                return;
            list($prop, $value) = explode("=", $property);
            if(isset($key_map[$prop]) || preg_match("/(.+)_(\d+)$/", $prop, $matches) && isset($matches[1], $matches[2], $key_map[$matches[1]]))
            {
                if(strlen($value) == 0)
                {
                    if(isset($global_conf[$prop]))
                        unset($global_conf[$prop]);
                }
                else
                    $global_conf[$prop] = $value;
            }
        }
        self::write_global_conf($global_conf);
    }

    //Exports reports from Appliance
    public function export_reports()
    {
        if(!$this->start_session_for_file_request(true, "gzip"))
            return;
        $data = $this->validate_arguments_for_post(array("display_names"));
        $data["display_names"] = explode(",", urldecode($data["display_names"]));

        require_once("custom_report_controller.php");
        if(($result = custom_report_controller::export_reports($data)) === SUCCESS_RESULT)
        {
            $this->output->set_header("Cache-Control: ");
            $this->output->set_header("Content-Type: application/" . $this->current_request_file_type);
            $this->output->set_header("Content-Disposition: attachment; filename=\"reports_" . date('m_d_Y_H_i_s') . ".gz\"");
            $this->output->set_output($data["output"]);
        }
        else
            $this->print_error_for_file_request($result, false, true);
    }

    //Imports reports to Appliance
    public function import_reports()
    {
        if(!$this->start_session_for_file_request(true, "gzip"))
            return;
        $data = $this->validate_arguments_for_post(array("MAX_FILE_SIZE"));

        require_once("custom_report_controller.php");
        if(($result = custom_report_controller::import_reports()) === SUCCESS_RESULT)
            $this->print_error_for_file_request("Reports are imported successfully.", false, true);
        else
            $this->print_error_for_file_request($result);
    }

    private function get_reporting_state(&$data)
    {
        $this->execute_command("getreporting", "");
        $result = $this->get_model()->get_result();
        if($result["rc"] == 0 && count($result["List"]) > 0 && isset($result["List"][0]["state"]) &&
           $result["List"][0]["state"] != NS_S_ENABLED)
            $data["reporting_disabled"] = true;
    }
}
?>
