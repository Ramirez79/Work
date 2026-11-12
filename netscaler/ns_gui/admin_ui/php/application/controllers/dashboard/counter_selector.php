<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
require_once(APPPATH . "views/common/view_utils.php");
class counter_selector
{
    static function retrieve_counter_groups($_ns_stat_definitions)
    {
        $counter_groups = array();
        $_ns_stat_definitions = new _ns_stat_definitions();
        $feature = $_ns_stat_definitions->feature;
        $stats_group = view_utils::get_reporting_group_array();
        foreach($stats_group as $name => $descr)
        {
            $cmd = $_ns_stat_definitions->get_command($name);
            $license = null;
            if(isset($cmd["license"]))
            {
                $license = array();
                foreach($cmd["license"] as $lic)
                    $license[] = strtolower($feature[$lic]);
            }
            $descr = preg_replace("/Statistics/", "", $descr);
            $counter_groups[] = new counter_group($name, $descr, $license);
        }
        return $counter_groups;
    }

    static function retrieve_counters($_ns_stat_definitions, $group)
    {
        $counters = array();
        $_ns_stat_definitions = new _ns_stat_definitions();
        $nsstat_cmd = $_ns_stat_definitions->get_command($group);
        if($nsstat_cmd)
        {
            $nsstat_cntrs = $nsstat_cmd["cntrs"];
            foreach($nsstat_cntrs as $counter_name => $counter)
            {
                if(!view_utils::can_counter_be_shown($counter, $group))
                    continue;
                $counter_desc = view_utils::get_counter_descr($counter, $nsstat_cntrs, true);
                $counter_tooltip = isset($counter["tooltip"]) ? $counter["tooltip"] : $counter_desc;
                $counters[] = new counter($counter_name, $counter_desc, $counter_tooltip, $group);
            }
        }
        return $counters;
    }
}

class counter_group
{
    private $name;
    private $descr;
    private $license;

    public function __construct($name, $descr, $license)
    {
        $this->name = $name;
        $this->descr = $descr;
        $this->license = $license;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_descr()
    {
        return $this->descr;
    }

    public function get_license()
    {
        return $this->license;
    }
}

class counter
{
    private $name;
    private $descr;
    private $tooltip;
    private $group;

    public function __construct($name, $descr, $tooltip, $group)
    {
        $this->name = $name;
        $this->descr = $descr;
        $this->tooltip = $tooltip;
        $this->group = $group;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_descr()
    {
        return $this->descr;
    }

    public function get_tooltip()
    {
        return $this->tooltip;
    }

    public function get_group()
    {
        return $this->group;
    }
}
?>