<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class agee extends abstract_controller
{
    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
    }

    public function get_alerts()
    {
        $alerts = array();
        if(!$this->is_ha())
            $alerts[] = array("text" => "High Availability is not configured");
        if(!$this->is_sta())
            $alerts[] = array("text" => "STA Server is not configured");
        return array("agee_alerts" => $alerts);
    }

    public function is_ha()
    {
        $this->execute_command("gethanode", array());
        $result = $this->get_model()->get_result();
        return ($result["rc"] != 0 || count($result["List"]) != 1);
    }

    public function is_sta()
    {
        $this->execute_command("getvpnglobal_staserver_binding", array());
        $result = $this->get_model()->get_result();
        return ($result["rc"] != 0 || count($result["List"]) > 0);
    }
}
?>