<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
require_once(APPPATH. "controllers/common/branding/abstract_branding.php");
class bytemobile_branding extends abstract_branding
{
    private  $brand_login_css;
    private $brand_name;
    private $brand_logo;
    private $brand_link;
    private $copyright_link;
    private $copyright_name;

    function bytemobile_branding()
    {
        $this->brand_login_css = "ns_login_logo";
        $this->brand_name = "NetScaler T-Series";

        if(get_sysid() == BYTEMOBILE_SYSID_T1100) 
            $this->brand_name = "NetScaler T-Series T1100";        
        if(get_sysid() == BYTEMOBILE_SYSID_T1010) 
            $this->brand_name = "NetScaler T-Series T1010";
        if(get_sysid() == BYTEMOBILE_SYSID_T1200)
            $this->brand_name = "NetScaler T-Series T1200";
        if(get_sysid() == BYTEMOBILE_SYSID_T1300)
            $this->brand_name = "NetScaler T-Series T1300";
        if(get_sysid() == BYTEMOBILE_SYSID_T1300_40G)
            $this->brand_name = "NetScaler T-Series T1300";
        if(get_sysid() == BYTEMOBILE_SYSID_T1120)
            $this->brand_name = "NetScaler T-Series T1120";

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
        return $_SESSION["nsbrandDesc"];
    }

    public function show_help()
    {
        return true;
    }
}

?>
