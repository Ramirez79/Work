<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * All values read through URL, POST body, cookie etc should be validated through this class.
 * Define all valid values for a particular key in get_valid_values() by adding a switch-case.
 * Define a default value for a particular key in get_default_value() by adding a switch-case.
 * Then call validate() to validate "user provided value" for that key.
 * If the value is from cookie, call validate_and_get_cookie().
 */
class input_validator
{
    function get_default_value($key)
    {
        switch($key)
        {
            case "startupapp":
                return "neo";
            case "unit":
                return "Minutes";
            case "jvm_memory":
                return "256M";
            case "timezone_offset":
                return 0;
            case "taw":
                return 80;
            case "tfs":
                return 9;
            case "stps":
                return 25;
            default:
                return "";
        }
    }

    function get_valid_values($key)
    {
        switch($key)
        {
            case "startupapp":
                return array("st", "neo", "rep", "doc", "dw", "cb");
            case "timezone_offset":
                return "/^\-?\d{1,5}$/";
            case "taw":
                return "/^\d{2}$/";
            case "tfs":
                return "/^\d{1,2}$/";
            case "stps":
                return array(25, 50, 100, 200);
            case "props":
                return "/^[\w=;,:&]*$/";
            case "chart_name":
                return "/^(?!_)[\w \.:\-]+$/";
            case "funcs":
                return "/^[\w;_]+$/";
            case "cntrs":
                return "/^[\w;,_]+$/";
            case "ents":
                return "/^[\w _#.\@:=\-\/%\?,]+$/";
            default:
                return null;
        }
    }

    function validate($key, $value)
    {
        $valid_values = self::get_valid_values($key);
        if(!$valid_values)
            return false;
        if(is_array($valid_values))
            return in_array($value, $valid_values);
        else //regex
            return !is_array($value) && preg_match($valid_values, $value);
    }

    function validate_and_get($key, $value)
    {
        if(self::validate($key, $value))
            return $value;
        return self::get_default_value($key);
    }

    function validate_and_get_cookie($key)
    {        
        if(isset($_COOKIE[$key]))
        {
            if(self::validate($key, $_COOKIE[$key]))
                return $_COOKIE[$key];
            else
            {
                abstract_controller::delete_cookie($key);
                unset($_COOKIE[$key]);
            }
        }
        return self::get_default_value($key);
    }
}
?>
