<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
require_once(APPPATH. "controllers/common/branding/abstract_branding.php");
class citrix_branding extends abstract_branding
{
    private $brand_login_css;
    private $brand_name;
    private $brand_logo;
    private $brand_link;
    private $copyright_link;
    private $copyright_name;

    function citrix_branding()
    {
        $this->brand_login_css = "ns_login_logo";
        $this->brand_name = "Citrix";
        $this->brand_logo = "citrix_logo";
        $this->brand_link = "http://www.citrix.com/netscaler";
        $this->copyright_link = "http://www.citrix.com/";
        $this->copyright_name = "Citrix Systems, Inc.";
    }

    public function get_brand_login_css()
    {
        return $this->brand_login_css;
    }

    public function get_brand_name()
    {
        return $this->brand_name;
    }

    public function get_brand_logo()
    {
        return $this->brand_logo;
    }

    public function get_brand_link()
    {
        return $this->brand_link;
    }

    public function get_copyright_link()
    {
        return $this->copyright_link;
    }

    public function get_copyright_name()
    {
        return $this->copyright_name;
    }

    public function get_title()
    {
        $brandDesc = $_SESSION["nsbrandDesc"];
        if(!preg_match("/^Citrix /", $brandDesc))
            $brandDesc = "Citrix " . $brandDesc;

        return $brandDesc;
    }

    public function show_help()
    {
        return true;
    }
}
?>
