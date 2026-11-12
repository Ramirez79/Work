<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
require_once(APPPATH. "controllers/common/branding/citrix_branding.php");
class agee_branding extends citrix_branding
{
   public function get_applinks()
   {
        return array(
            array("link" => "/menu/agee?standalone=no", "text" => "Home", "value" => "agee"),
            array("link" => "/menu/st", "text" => "Dashboard", "value" => "st"),
            array("link" => "/menu/neo", "text" => "Configuration", "value" => "neo", "default" => true),
            array("link" => "/menu/rep", "text" => "Reporting", "value" => "rep")
        );
    }
}
?>
