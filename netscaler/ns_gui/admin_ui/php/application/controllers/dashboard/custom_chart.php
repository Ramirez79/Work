<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
//This controller acts as an adaptor to custom comparative charts file
class custom_chart
{
    private $custom_charts = array();

    public function __construct()
    {
        $this->load_json();
    }

    private function load_json()
    {
        if(file_exists(CUSTOM_CHARTS_FILE_PATH))
        {
            if(($json = file_get_contents(CUSTOM_CHARTS_FILE_PATH)) !== false)
                $this->custom_charts = json_decode($json, true);
        }
    }

    private function save_json()
    {
        if(!file_exists(CUSTOM_CHARTS_DIR) || file_put_contents(CUSTOM_CHARTS_TEMP_FILE_PATH, json_encode($this->custom_charts)) === false)
        {
            if(file_exists(CUSTOM_CHARTS_TEMP_FILE_PATH))
                exec("rm '" . CUSTOM_CHARTS_TEMP_FILE_PATH . "'");
            $this->print_error_message("ERROR_WRITING_CHARTS_FILE");
        }
        else
        {
            exec("mv '" . CUSTOM_CHARTS_TEMP_FILE_PATH . "' '" . CUSTOM_CHARTS_FILE_PATH . "'");
            print SUCCESS_RESULT;
        }
    }

    private function print_error_message($error_message, $error_prefix = "")
    {
        require_once(APPPATH . "controllers/common/utils.php");
        print $error_prefix . utils::get_error_message($error_message);
    }

    public function get_charts()
    {
        return array(array("name" => "Custom", "nodes" => $this->custom_charts));
    }

    public function get_chart($chart_name, $return_index = false)
    {
        for($index = 0, $end_index = count($this->custom_charts); $index < $end_index; $index++)
        {
            if($this->custom_charts[$index]["name"] == $chart_name)
                return $return_index ? $index : $this->custom_charts[$index];
        }
        return $return_index ? -1 : null;
    }

    public function save_chart($data)
    {
        $chart_name = $data["chart_name"];
        $definition = $data["definition"];
        if(isset($definition["ents"]) && count($definition["ents"]) == 0)
            unset($definition["ents"]);

        $index = $this->get_chart($chart_name, true);
        if($index != -1)
        {
            if($data["overwrite"])
            {
                $this->custom_charts[$index] = $definition;
                $this->save_json();
            }
            else
                print CHART_ALREADY_EXISTS_RESULT;
        }
        else
        {
            if(count($this->custom_charts) < MAX_NO_OF_CUSTOM_CHARTS)
            {
                $this->custom_charts[] = $definition;
                $this->save_json();
            }
            else
                $this->print_error_message("MAX_CUSTOM_CHARTS");
        }
    }

    public function delete_chart($data)
    {
        $chart_name = $data["chart_name"];
        $index = $this->get_chart($chart_name, true);
        if($index != -1)
        {
            array_splice($this->custom_charts, $index, 1);
            $this->save_json();
        }
        else
            $this->print_error_message("ERROR_CHART_NOT_FOUND", "Error while deleting chart [$chart_name].\n");
    }
}
?>