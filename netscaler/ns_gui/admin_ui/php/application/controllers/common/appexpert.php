<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

class appexpert extends abstract_controller
{
    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
    }

    //Given an application unit name (lbvserver name), returns its curstate & bound policies count
    //If getlbvserver (for curstate) fails, an error will be returned.
    //If getpolicycount fails, that policy will be ignored
    //Ex: {name: app1, curstate: down, cachepolicy_count: 0, cmppolicy_count: 2}
    public function get_appunit_details($appunit_name)
    {
        $response = array();
        $policy_types = array("cachepolicy", "cmppolicy");

        if(!$this->populate_basic_details($response, $appunit_name))
            return $response;

        foreach($policy_types as $policy_type)
            $this->populate_policy_count($response, $appunit_name, $policy_type);

        return $response;
    }

    private function populate_basic_details(&$response, $appunit_name)
    {
        $this->execute_command("getlbvserver", array("name" => $appunit_name));
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0)
        {
            $this->populate_error($response, $result);
            return false;
        }
        $this->populate_field($response, "name", $appunit_name);
        $this->populate_field($response, "curstate", $result["List"][0]["curstate"]);
        return true;
    }

    private function populate_policy_count(&$response, $appunit_name, $policy_type)
    {
        $this->execute_command("getlbvserver_" . $policy_type . "_binding", array("name" => $appunit_name, NITRO_MODEL_COUNT_PARAM => "yes"));
        $result = $this->get_model()->get_result();
        if($result["rc"] == 0 && count($result["List"]) > 0)
            $this->populate_field($response, $policy_type . "_count", $result["List"][0]["__count"]);
    }

    private function populate_error(&$response, $result)
    {
        $response["errorcode"] = $result["rc"];
        $response["message"] = $result["message"];
    }

    private function populate_field(&$response, $field, $value)
    {
        if(!isset($response["appunit_details"]))
            $response["appunit_details"] = array();

        $response["appunit_details"][$field] = $value;
    }

    public function get_app_templates()
    {
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        $response = array();
        rapi_utils::populate_file_listing($response, APP_TEMPLATES_DIR, "app_templates", "/^(.*).xml$/");
        return $response;
    }

    public function get_lb_entity_templates()
    {
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        $response = array();
        rapi_utils::populate_file_listing($response, LB_ENTITY_TEMPLATES_DIR, "lb_entity_templates", "/^(.*).xml$/");
        return $response;
    }
}
?>