<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . "controllers/common/abstract_controller.php");

class dashboard extends abstract_controller
{
    public function main()
    {
        ob_start("ob_gzhandler");
        $data = $this->validate_arguments(func_get_args(), array(), array("cfunc", "outputformat", "rno", "evtno"));

        $this->load->helper('cookie');
        set_cookie("startupapp","st", 60*60*24*30*12);
        
        if(!isset($data["outputformat"]) || !in_array($data["outputformat"], array("HTML", "DATA")))
            $data["outputformat"] = "HTML";
        $this->validate_request_number($data);
        $this->validate_event_number($data);
        if(isset($data["cfunc"]))
        {
            require_once("_ns_stat_definitions.php");
            require_once(APPPATH . "controllers/common/utils.php");
            if (!utils::validate_function($data["cfunc"]))
                self::show_error_page("INVALID_FUNC");
        }

        if($this->is_ajax_request($data) == true)
        {
            if(!$this->start_session_for_ajax_request())
                return;
        }
        else if(!$this->start_session())
            return;

        if(isset($_SESSION["nsbrandDesc"]))
            $data["title"] = get_branding()->get_title() . " - Dashboard";

        $this->retrieve_data_for_top_gauges($data);
        $this->load->view("dashboard/dashboard", $data);
    }

    public function content()
    {
        ob_start("ob_gzhandler");
        $data = $this->validate_arguments(func_get_args(), array(), array("func", "argname", "argval", "outputformat", "detailview", "sid", "rno", "evtno", "username", "funcs", "cntrs", "ents", "popup", "pageno", "fromconfig"));
        $this->validate_request_number($data);
        $this->validate_event_number($data);
        //In webstart case, token is passed via URL.
        //In other cases, check for a valid server side session
        $ajax_request = $this->is_ajax_request($data);
        $data["is_ajax_request"] = $ajax_request;
        if(!isset($data['sid']))
        {
            if($ajax_request)
            {
                if(!$this->start_session_for_ajax_request())
                    return;
            }
            else if(!$this->start_session())
                return;
        }
        else
            utils::setup_webstart_session($data['sid']);
        $data["standalone"] = "YES";
        $data["no_footer_link"] = true;
        if(isset($_SESSION['nsbrandDesc']))
            $data['title'] = get_branding()->get_title() . ' - Statistics';

        require_once("_ns_stat_definitions.php");
        require_once(APPPATH . "controllers/common/utils.php");
        require_once(APPPATH . "views/common/view_utils.php");
        $result = "";
        //Validate arguments to prevent XSS attacks
        $parameter = utils::validateParams($data);
        $function = $data['func'];
        $this->setup_page_rno($data, ST_PAGE_NUMBER_KEY);
        $data[ST_PAGE_NUMBER_KEY] = $_SESSION[ST_PAGE_NUMBER_KEY];

        //Cache result only if it has to be shared with charts
        $use_cache = false;
        $display_handlers = $data["cmd"]["displayhandler"];
        foreach($display_handlers as $display_handler)
        {
            if(isset($display_handler["charts"]))
            {
                $use_cache = true;
                break;
            }
        }

        $this->add_binding_query_params($function, $parameter);
        $this->execute_command($function, $parameter, $use_cache, $data['rno']."_".$function);
        $result = $this->get_model()->get_result();
        $result = $this->add_bindings_to_result($result, $function, $parameter);
        
        //result timestamp is always in GMT. To show time in user timezone, we don't need to do anything.
        //GMT epoch will be passed onto JS where it gets converted into client timezone.
        //To show time in appliance timezone, we have to convert GMT timestamp to our timezone (add date('Z'))
        //and then subtract client timezone offset from GMT (as JS will add client timezone offset from GMT which should be nullified)
        //So equation is (timestamp + date('Z') - client_timezone_offset) where client_timezone_offset = $_SESSION["timezone_offset"] + date('Z')
        //(timestamp + date('Z') - ($_SESSION["timezone_offset"] + date('Z'))) which is simplified further as shown below.
        if(isset($_SESSION["timezone_offset"], $_COOKIE["uatz"]) && $_COOKIE["uatz"] == "uatz")
            $result["timestamp"] -= $_SESSION["timezone_offset"];

        $data['result'] = $result;
        $data["popup_window"] = isset($data["popup"]) && $data["popup"] == "yes";
        if($result && isset($data['sid']))
        {
            //In webstart case, sid is valid and hence start a session
            utils::setup_webstart_user_session($data['sid'], isset($data["username"]) ? $data["username"] : "");
            if(isset($data[ST_PAGE_NUMBER_KEY]))
                $_SESSION[ST_PAGE_NUMBER_KEY] = $data[ST_PAGE_NUMBER_KEY];
        }
        $data["is_graphical_view_supported"] = $this->is_graphical_view_supported($function);
        $data["is_graphical_view"] = !$data["is_graphical_view_supported"] ? false : $this->is_graphical_view();

        $data["_ns_stat_definitions"] = new _ns_stat_definitions();
        $this->populate_available_counters_groups($data, $function);
        $data["graphical_view_definition"] = $this->get_graphical_view_definition($data["cmd"]);
        $this->populate_selected_counters_entities($data, $data["graphical_view_definition"]);
        $data["rand_key"] = $_SESSION["rand"];
        $fromConfig=false;
        if(isset($data["fromconfig"]) && $data["fromconfig"] === "yes")
            $fromConfig = true;
        else
            $fromConfig = false;
        $data["fromconfig"] = $fromConfig;
        $this->load->view("dashboard/stats", $data);
    }

    private function add_binding_query_params(&$function, &$parameter)
    {
        if($function == "statservicegroup")
        {
            $parameter["statbindings"] = "yes";
        }
        
        if($function == "statlbvserver")
        {
            $parameter["statbindings"] = "yes";
        }
        
        if($function == "statgslbvserver")
        {
            $parameter["statbindings"] = "yes";
        }
        
        if($function == "statgslbsite")
        {
            $parameter["statbindings"] = "yes";
        }
    }

    public function add_bindings_to_result($result, $function, $parameter)
    {
        if(count($result["List"]) > 0)
        {
            if($function == "statservicegroup")
            {
                $result["List"][0]["entitytype"] = "statservicegroup";
                if(isset($result["List"][0]["servicegroupmember"]))
                {
                    $result = $this->get_stat_bindings($result, "servicegroupmember", "statservicegroupmember");
                }
            }

            if($function == "statlbvserver")
            {
                $result["List"][0]["entitytype"] = "statlbvserver";
                if(isset($result["List"][0]["service"]))
                {
                    $result = $this->get_stat_bindings($result, "service", "statservice");
                }

                if(isset($result["List"][0]["servicegroupmember"]))
                {
                    $result = $this->get_stat_bindings($result, "servicegroupmember", "statservicegroupmember");
                }

            }

            if($function == "statgslbvserver")
            {
                $result["List"][0]["entitytype"] = "statgslbvserver";
                if(isset($result["List"][0]["gslbservice"]))
                {
                    $result = $this->get_stat_bindings($result, "gslbservice", "statgslbservice");
                }
            }
            
            if($function == "statgslbsite" && isset($parameter["sitename"]))
            {
                $result["List"][0]["entitytype"] = "statgslbsite";
                if(isset($result["List"][0]["gslbservice"]))
                {
                    $result = $this->get_stat_bindings($result, "gslbservice", "statgslbservice");
                }
            }       
        }

        return $result;
    }
    
    private function get_stat_bindings(&$result, $enitity_name, $stat_command)
    {
        $lbvserver = $result["List"][0];
        $services = $lbvserver[$enitity_name];
        $length = count($services);

        for($i=0;$i<$length;$i++)
        {
            $services[$i]["entitytype"] = $stat_command;
            $result["List"][] = $services[$i];       
        }
        
        unset($result["List"][0][$enitity_name]);
        return $result;
    }
    
    //This call is to clear cache of stat command's result (which is stored in $_SESSION)
    //This is invoked on unload of any stats page
    public function clear_cached_result()
    {
        if(!$this->start_session_for_ajax_request(false, false))
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('func', 'rno'));
        if(!$this->validate_request_number($data))
            return;

        //Clear the cache for the previous as well as current request numbers
        $this->clear_cache($data, true);
    }

    //Handles all types of chart requests.
    //It expects chart_name defined in chart_definitions.php. It invokes the stat command associated with
    //that chart and passes the result and chart definition to the view, which then prints the chart.
    public function chart()
    {
        if(!$this->start_session())
            return;
        $data = $this->validate_arguments(func_get_args(), array('name'), array('rno', 'argname', 'argval'));
        $this->validate_request_number($data);

        //Get chart definition from name
        require_once("chart_definitions.php");
        $chart_definitions = new chart_definitions();
        $chart_definition = $chart_definitions->get_chart_definition($data['name']);
        if($chart_definition == null)
            exit("Chart definition not found");

        //Get command associated with the chart
        if(isset($chart_definition['cmd']))
            $data["func"] = $chart_definition['cmd'];
        else
            exit("Chart command is not specified");

        $page_number_key = ($data["name"] == "cpu_mem_dial" ? GAUGES_PAGE_NUMBER_KEY : ST_PAGE_NUMBER_KEY);
        $this->setup_rno($data, $page_number_key);
        $parameter = '';
        if(isset($chart_definition['entity_cmd'], $data['argname'], $data['argval']) && $chart_definition['entity_cmd'])
            $parameter[$data['argname']] = urldecode(preg_replace("/%252F/", "/", $data['argval']));
        if(isset($data["rno"]))
            $this->execute_command($data["func"], $parameter, true, $data["rno"] . "_" . $data["func"]);
        else
            $this->execute_command($data["func"], $parameter);
        $result = $this->get_model()->get_result();

        //Pass chart definition and result to chart view
        $args['chart_definition'] = $chart_definition;
        $args['result'] = $result;
        $this->load->view('dashboard/chart', $args);
    }

    //Executes the enable/disable command and ignores the result
    public function do_operation()
    {
        if(!$this->start_session_for_ajax_request())
            return;
        $data = $this->validate_arguments_for_post(array('cmd', 'argval'));
        $stfunc = preg_replace("/^enable|disable/", "stat", $data['cmd']);
        require_once("_ns_stat_definitions.php");
        $_ns_stat_definitions = new _ns_stat_definitions();
        $cmd = $_ns_stat_definitions->get_command($stfunc);
        require_once(APPPATH."controllers/common/utils.php");
        $argname = utils::get_argname($cmd);
        if(!utils::validate_function($stfunc))
            $this->show_error_page("INVALID_FUNC");
        $data['argval'] = explode(",", urldecode(preg_replace("/%252F/", "/", $data['argval'])));
        foreach($data['argval'] as $argval)
        {
            if(preg_match("/;" . NSI_T_SVCGRP_SVC . "$/", $argval))
            {
                $argval = preg_replace("/;" . NSI_T_SVCGRP_SVC . "$/", "", $argval);
                if(!($parameters = utils::get_parameters_for_service_group_member($argval)))
                    continue;
                $this->execute_command(preg_replace("/service$/", "servicegroup", $data['cmd']), $parameters);
                continue;
            }
            if(!utils::validate_argvalue($argval, $cmd['nameargtype']))
                $this->show_error_page("INVALID_ARG");
            $parameters = array(
                $argname    =>  $argval,
            );
            $this->execute_command($data['cmd'], $parameters);
        }
    }

    //Dashboard (public end points and application units) for Applications View
    public function application()
    {
        $data = $this->validate_arguments_for_post(array('pe', 'appname'), array('au', 'sid', 'outputformat', 'evtno', 'rno', "username"), false);
        $this->validate_request_number($data);
        $this->validate_event_number($data);
        require_once(APPPATH."controllers/common/utils.php");
        require_once(APPPATH."views/common/view_utils.php");
        if(!isset($data['sid']))
        {
            if($this->is_ajax_request($data))
            {
                if(!$this->start_session_for_ajax_request())
                    return;
            }
            else if(!$this->start_session())
                return;
        }
        else
            utils::setup_webstart_session($data['sid']);

        //Validate arguments
        $data['pe'] = preg_replace("/,$/", "", urldecode($data['pe']));
        $cs_vips = explode(",", $data['pe']);
        foreach($cs_vips as $cs_vip)
        {
            if(!utils::validate_argvalue($cs_vip, CS_ARG_TYPE))
                $this->show_error_page("INVALID_ARG");
        }

        $data['appname'] = urldecode($data['appname']);
        if(!utils::validate_argvalue($data['appname'], CS_ARG_TYPE))
            $this->show_error_page("INVALID_ARG");

        if(isset($data['au']))
        {
            $data['au'] = preg_replace("/,$/", "", urldecode($data['au']));
            $lb_vips = explode(",", $data['au']);
            foreach($lb_vips as $lb_vip)
            {
                if(!utils::validate_argvalue($lb_vip, LB_ARG_TYPE))
                    $this->show_error_page("INVALID_ARG");
            }
        }
        $data['standalone'] = "YES";
        $data['title'] = "Statistics for " . $data['appname'];

        //Fetch stats for all csvservers
        $this->execute_command(CS_GROUP, '');
        $cs_result = $this->get_model()->get_result();
        if(!$this->validate_result($cs_result, $data, PUBLIC_END_POINTS))
            return;

        require_once("stapp_definitions.php");
        $stapp_units = array();
        $stapp_defs = new stapp_definitions();
        $cs_group = $stapp_defs->get_group(CS_GROUP);
        $stapp_units[] = new stapp_unit($data['pe'], $cs_result, $cs_group);

        //Fetch stats for all lbvservers
        if(isset($data['au']))
        {
            $this->execute_command(LB_GROUP, '');
            $lb_result = $this->get_model()->get_result();
            if(!$this->validate_result($lb_result, $data, APP_UNITS))
                return;

            $lb_group = $stapp_defs->get_group(LB_GROUP);
            $stapp_units[] = new stapp_unit($data['au'], $lb_result, $lb_group);
        }
        $data['stapp_units'] = $stapp_units;
        $data["base_url"] = "/menu/stapp/";
        $this->retrieve_data_for_top_gauges($data);
        $this->load->view("dashboard/stapp", $data);
    }

    //Dashboard (bound services) for Applications View
    public function application_unit()
    {
        $data = $this->validate_arguments_for_post(array('au', 'appname'), array('outputformat', 'evtno', 'rno', "username"), false);
        $this->validate_request_number($data);
        $this->validate_event_number($data);
        require_once(APPPATH."controllers/common/utils.php");
        require_once(APPPATH."views/common/view_utils.php");
        if($this->is_ajax_request($data))
        {
            if(!$this->start_session_for_ajax_request())
                return;
        }
        else if(!$this->start_session())
            return;

        //Validate arguments
        $data['au'] = urldecode($data['au']);
        if(!utils::validate_argvalue($data['au'], LB_ARG_TYPE))
            $this->show_error_page("INVALID_ARG");

        $data['appname'] = urldecode($data['appname']);
        if(!utils::validate_argvalue($data['appname'], CS_ARG_TYPE))
            $this->show_error_page("INVALID_ARG");

        $data['standalone'] = "YES";
        $data['title'] = "Statistics for " . view_utils::get_displayable_app_name($data['au'], $data['appname']);

        //Fetch stats for the lbvserver along with bindings
        $parameters = array(
            LB_ARG_NAME     =>  $data['au'],
            'bindings'      =>  1,
        );
        $this->execute_command(LB_GROUP, $parameters);
        $lb_result = $this->get_model()->get_result();
        if(!$this->validate_result($lb_result, $data, BOUND_SERVICES))
            return;

        $svc_entities = "";
        foreach($lb_result['List'] as $result_obj)
        {
            if(!isset($result_obj['entitytype']) || $result_obj['entitytype'] != SVC_GROUP)
                continue;
            if($svc_entities != "")
                $svc_entities = $svc_entities . ",";
            $svc_entities = $svc_entities . preg_replace("/\"/", "", $result_obj[SVC_ARG_NAME]);
        }
        if($svc_entities == "") //There are no bound services to this app unit
        {
            print $this->load->view("common/header", $data, true);
            view_utils::print_error_string(BOUND_SERVICES . " not available");
            print $this->load->view("common/footer", $data, true);
            return;
        }

        require_once("stapp_definitions.php");
        $stapp_units = array();
        $stapp_defs = new stapp_definitions();
        $svc_group = $stapp_defs->get_group(SVC_GROUP);
        $stapp_units[] = new stapp_unit($svc_entities, $lb_result, $svc_group);
        $data['stapp_units'] = $stapp_units;
        $data["base_url"] = "/menu/stau/";
        $this->retrieve_data_for_top_gauges($data);

        $this->load->view("dashboard/stapp", $data);
    }

    //Validates result and prints messages on error
    private function validate_result($result, &$data, $disp_name)
    {
        if($result['rc'] != 0)
        {
            if($this->is_ajax_request($data))
                view_utils::print_error_result_xml($result, $disp_name);
            else
            {
                print $this->load->view("common/header", $data, true);
                view_utils::print_error_result_page($result, $disp_name);
                print $this->load->view("common/footer", $data, true);
            }
            return false;
        }
        if(isset($data['sid'])) //In webstart case, sid is valid and hence start a session
        {
            utils::setup_webstart_user_session($data['sid'], isset($data["username"]) ? $data["username"] : "");
            unset($data['sid']); //Start the session only once
        }
        if(!isset($result['List']) || count($result['List']) == 0)
        {
            if($this->is_ajax_request($data))
            {
                header(XML_HEADER);
                print "<messages>\n</messages>";
            }
            else
            {
                print $this->load->view("common/header", $data, true);
                view_utils::print_error_string("$disp_name not available");
                print $this->load->view("common/footer", $data, true);
            }
            return false;
        }
        return true;
    }

    public static function post_params_to_string($params_delimiter = "&", $key_value_delimiter = "=")
    {
        if(!is_array($_POST) || count($_POST) <= 0)
            return null;
        $post_data = "";
        foreach($_POST as $key=>$value)
        {
            if($post_data != "")
                $post_data .= $params_delimiter;
            $post_data .= $key . $key_value_delimiter. $value;
        }
        return $post_data;
    }

    private function is_graphical_view_supported($function)
    {
        return !in_array($function, array("statservicegroup"));
    }

    //Determines the default view (tabular or graphical)
    //Similar function is defined in dashboard.js
    private function is_graphical_view()
    {
        //tmon was earlier used to detect tabular view or not which is replaced with gmon
        $this->delete_cookie("tmon");
        return (isset($_COOKIE["gmon"]) && $_COOKIE["gmon"] == "gmon");
    }

    public function comparative_chart()
    {
        $data = $this->validate_arguments(func_get_args(), array("id"), array("name", "outputformat", "rno", "funcs", "cntrs", "ents", "popup"));
        $this->validate_request_number($data);
        $data["is_ajax_request"] = $this->is_ajax_request($data);
        if($data["is_ajax_request"])
        {
            if(!$this->start_session_for_ajax_request())
                return;
        }
        else if(!$this->start_session())
            return;
        if(!$data["is_ajax_request"])
        {
            $data["standalone"] = "YES";
            $data["outputformat"] = "HTML";
            $data["no_footer_link"] = true;
            $data["no_loading_content"] = true;
            $brand_title = get_branding()->get_title();
            $data["title"] = isset($brand_title) ? $brand_title . " - Comparative Chart" : "Comparative Chart";
        }
        $data["popup_window"] = isset($data["popup"]) && $data["popup"] == "yes";

        if(!$this->pick_and_validate_comparative_chart($data))
            return;
        $this->populate_selected_counters_entities($data, $data["comparative_chart"]);

        require_once("_ns_stat_definitions.php");
        $data["_ns_stat_definitions"] = new _ns_stat_definitions();
        $functions = array();
        $cmds = array();
        $results = array();
        $functions = array_keys(isset($data["comparative_chart"]["func_cntrs"]) ? $data["comparative_chart"]["func_cntrs"] : $data["comparative_chart"]["alt_expr"]);
        foreach($functions as $function)
        {
            if(!isset($cmds[$function]))
            {
                if(!($cmds[$function] = $data["_ns_stat_definitions"]->get_command($function)))
                    $this->show_error_page("INVALID_FUNC");
            }
            if(!isset($results[$function]))
            {
                $this->execute_command($function, "");
                $results[$function] = $this->get_model()->get_result();
                if($results[$function]["rc"] == NSERR_NOTAUTHORIZED)
                {
                    require_once(APPPATH . "views/common/view_utils.php");
                    print $this->load->view("common/header", $data, true);
                    view_utils::print_error_string($results[$function]["message"]);
                    print $this->load->view("common/footer", $data, true);
                    return;
                }
            }
        }
        $data["cmds"] = $cmds;
        $data["results"] = $results;

        if($data["popup_window"])
            $this->populate_available_counters_groups($data, $functions[0]);
        $this->load->view("dashboard/comparative_chart", $data);
    }

    private function pick_and_validate_comparative_chart(&$data)
    {
        $id = $data["id"];
        $cookie_name = "coc" . $id;
        if(!in_array($id, array("0", "1")))
            $this->show_error_page("INVALID_COC_NAME");

        require_once("comparative_chart_definitions.php");
        require_once("custom_chart.php");
        $data["comparative_chart_definitions"] = new comparative_chart_definitions();
        $data["custom_chart"] = new custom_chart();

        if(isset($data["name"]))
        {
            $data["name"] = urldecode($data["name"]);
            if($this->pick_report($data) && !$data["popup_window"] && !$data["is_ajax_request"])
            {
                $this->load->helper("cookie");
                set_cookie($cookie_name, $data["name"], 60*60*24*30*12);
            }
        }
        if(!isset($data["name"]) && isset($_COOKIE[$cookie_name]))
        {
            $data["name"] = $_COOKIE[$cookie_name];
            if(!$this->pick_report($data))
                abstract_controller::delete_cookie($cookie_name);

        }
        if(!isset($data["name"]))
        {
            $data["name"] = $data["comparative_chart_definitions"]->get_default_comparative_chart_name($id);
            $this->pick_report($data);
        }
        return true;
    }

    private function pick_report(&$data)
    {
        $data["comparative_chart"] = $data["comparative_chart_definitions"]->get_comparative_chart($data["name"]);
        if($data["comparative_chart"] != null)
        {
            $data["custom"] = false;
            return true;
        }

        $data["comparative_chart"] = $data["custom_chart"]->get_chart($data["name"]);
        if($data["comparative_chart"] != null)
        {
            $data["custom"] = true;
            return true;
        }

        unset($data["name"]);
        return false;
    }

    private function get_graphical_view_definition($cmd)
    {
        $cmd_name = $cmd["cmdname"];

        require_once("graphical_view_definitions.php");
        $graphical_view_definitions = new graphical_view_definitions();
        $graphical_view_definition = $graphical_view_definitions->get_graphical_view_definition($cmd_name);
        if(isset($graphical_view_definition["cntrs"]))
            $graphical_view_definition["func_cntrs"] = array($cmd_name => $graphical_view_definition["cntrs"]);
        if(isset($graphical_view_definition["alt_expr"]))
            $graphical_view_definition["alt_expr"] = array($cmd_name => $graphical_view_definition["alt_expr"]);
        unset($graphical_view_definition["cntrs"]);
        if($graphical_view_definition)
            return $graphical_view_definition;

        //Probably a new command introduced which doesn't have a graphical_view_definition yet.
        $cntrs = $cmd["cntrs"];
        $dhs = $cmd["displayhandler"];
        foreach($dhs as $dh)
        {
            $counters = array();
            if(isset($dh["counters"]))
                $counters = $dh["counters"];
            else if(isset($dh["column1"]))
                $counters = $dh["column1"]["counters"];
            else if(isset($dh["column2"]))
                $counters = $dh["column2"]["counters"];
            foreach($counters as $counter_name)
            {
                if(!isset($cntrs[$counter_name]) || !view_utils::can_counter_be_shown($cntrs[$counter_name], $cmd_name))
                    continue;
                return array("func_cntrs" => array($cmd_name => array($counter_name)));
            }
        }
        return null;
    }

    private function populate_available_counters_groups(&$data, $function)
    {
        if(isset($data["is_ajax_request"]) && $data["is_ajax_request"] == true)
            return;
        require_once("counter_selector.php");
        $data["counter_groups"] = counter_selector::retrieve_counter_groups($data["_ns_stat_definitions"]);
        $data["counters"] = counter_selector::retrieve_counters($data["_ns_stat_definitions"], $function);
    }

    private function populate_selected_counters_entities($data, &$definition)
    {
        if(!$data["is_ajax_request"] || !$definition)
            return;
        if(!isset($data["cntrs"], $data["funcs"]))
        {
            $definition = null;
            return;
        }
        $funcs = explode(";", $data["funcs"]);
        $cntrs = explode(";", $data["cntrs"]);
        if(count($funcs) != count($cntrs))
        {
            $definition = null;
            return;
        }
        $func_cntrs = array();
        for($i = 0, $end_index = count($funcs); $i < $end_index; $i++)
            $func_cntrs[$funcs[$i]] = explode(",", $cntrs[$i]);
        $definition["func_cntrs"] = $func_cntrs;
        $definition["ents"] = isset($data["ents"]) ? explode(",", urldecode(preg_replace("/%252F/", "/", $data["ents"]))) : array();
    }

    //Handles counters popup request (on change of group)
    public function counters_popup()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array("func", "rand"));
        require_once("_ns_stat_definitions.php");
        require_once(APPPATH . "views/dashboard/counter_selector_view.php");
        $data["_ns_stat_definitions"] = new _ns_stat_definitions();
        if(!($cmd = $data["_ns_stat_definitions"]->get_command($data["func"])))
            $this->show_error_page("INVALID_FUNC");

        $this->populate_available_counters_groups($data, $data["func"]);

        $counter_selector = new counter_selector_view($data["_ns_stat_definitions"], $data["func"], $data["counter_groups"], $data["counters"], $this->retrieve_entities($cmd), "counter_selector", false);
        $counter_selector->render();
    }

    private function retrieve_entities($cmd)
    {
        $entities = array();
        if(!isset($cmd["namearg"]))
            return $entities;

        $function = $cmd["cmdname"];
        $argname = $cmd["namearg"];

        $this->execute_command($function, "");
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || !isset($result["List"]) || (count($result["List"]) == 0))
            return $entities;

        foreach($result["List"] as $obj)
        {
            if(isset($obj["entitytype"]) && ($obj["entitytype"] != $function))
                continue;
            $entities[] = preg_replace("/\"/", "", isset($obj[$argname]) ? $obj[$argname] : "0");
        }
        return $entities;
    }

    public function save_chart()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array("chart_name", "overwrite", "funcs", "cntrs"), array("ents"));
        $data["chart_name"] = urldecode($data["chart_name"]);
        $data["overwrite"] = ($data["overwrite"] == "true");

        if(!input_validator::validate("chart_name", $data["chart_name"]) ||
            !input_validator::validate("funcs", $data["funcs"]) ||
            !input_validator::validate("cntrs", $data["cntrs"]) ||
            (isset($data["ents"]) && !input_validator::validate("ents", $data["ents"])))
        {
            require_once(APPPATH."controllers/common/utils.php");
            print utils::get_error_message("INVALID_REQUEST");
            return;
        }

        $data["is_ajax_request"] = true;
        $data["definition"] = array("name" => $data["chart_name"]);
        $this->populate_selected_counters_entities($data, $data["definition"]);

        require_once("custom_chart.php");
        $custom_chart = new custom_chart();
        $custom_chart->save_chart($data);
    }

    public function delete_chart()
    {
        if(!$this->start_session_for_ajax_request(true))
            return;
        $data = $this->validate_arguments_for_post(array("chart_name"));
        $data["chart_name"] = urldecode($data["chart_name"]);
        require_once("custom_chart.php");
        $custom_chart = new custom_chart();
        $custom_chart->delete_chart($data);
    }
}
?>
