<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

define("DEFAULT_GROUPING_SEPARATOR", ",");
define("DEFAULT_DECIMAL_SEPARATOR", ".");

/* As we don't have locales installed in Netscaler, we have hard-coded decimal and grouping separators
 * for each locale which is determined using two digits language header in HTTP request as mentioned in
 * http://ftp.ics.uci.edu/pub/ietf/http/related/iso639.txt
 */
class locale_utils
{
    function set_up_locale()
    {
        $languages = array();
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            //break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8})(-[a-z]{1,8})?\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
            if(count($lang_parse[1]) && (count($lang_parse[1]) == count($lang_parse[4])))
            {
                $langs_array = $lang_parse[1];
                $quotients_array = $lang_parse[4];
                //create a list like "en" => 0.8 and set default to 1 for any without q factor and sort list
                //Take into account headers like en,de;q=0.7,en-us;q=0.3
                for($index = 0; $index < count($lang_parse[1]); $index++)
                {
                    $temp_val = ($quotients_array[$index] == '') ? 1 : $quotients_array[$index];
                    if(!isset($languages[$langs_array[$index]]) || ($languages[$langs_array[$index]] < $temp_val))
                        $languages[$langs_array[$index]] = $temp_val;
                }
                arsort($languages, SORT_NUMERIC);
            }
        }
        $_SESSION['grouping_separator'] = self::find_grouping_separator($languages);
        $_SESSION['decimal_separator'] = self::find_decimal_separator($languages);
    }

    function find_grouping_separator($languages)
    {
        //This map should be taken from the ouput of "ns/tools/PHPLocaleMapGenerator.java"
        $grouping_separator_map = Array("aa" => 44, "ab" => 44, "af" => 44, "am" => 44, "ar" => 44, "as" => 44, "ay" => 44, "az" => 44, "ba" => 44, "be" => 32, "bg" => 32, "bh" => 44, "bi" => 44, "bn" => 44, "bo" => 44, "br" => 44, "ca" => 46, "co" => 44, "cs" => 32, "cy" => 44, "da" => 46, "de" => 46, "dz" => 44, "el" => 46, "en" => 44, "eo" => 44, "es" => 46, "et" => 32, "eu" => 44, "fa" => 44, "fi" => 32, "fj" => 44, "fo" => 44, "fr" => 32, "fy" => 44, "ga" => 44, "gd" => 44, "gl" => 44, "gn" => 44, "gu" => 44, "ha" => 44, "he" => 44, "hi" => 44, "hr" => 46, "hu" => 32, "hy" => 44, "ia" => 44, "id" => 44, "ie" => 44, "ik" => 44, "is" => 46, "it" => 46, "iu" => 44, "ja" => 44, "jw" => 44, "ka" => 44, "kk" => 44, "kl" => 44, "km" => 44, "kn" => 44, "ko" => 44, "ks" => 44, "ku" => 44, "ky" => 44, "la" => 44, "ln" => 44, "lo" => 44, "lt" => 46, "lv" => 32, "mg" => 44, "mi" => 44, "mk" => 46, "ml" => 44, "mn" => 44, "mo" => 44, "mr" => 44, "ms" => 44, "mt" => 44, "my" => 44, "na" => 44, "ne" => 44, "nl" => 46, "no" => 32, "oc" => 44, "om" => 44, "or" => 44, "pa" => 44, "pl" => 32, "ps" => 44, "pt" => 46, "qu" => 44, "rm" => 44, "rn" => 44, "ro" => 46, "ru" => 32, "rw" => 44, "sa" => 44, "sd" => 44, "sg" => 44, "sh" => 46, "si" => 44, "sk" => 32, "sl" => 46, "sm" => 44, "sn" => 44, "so" => 44, "sq" => 46, "sr" => 32, "ss" => 44, "st" => 44, "su" => 44, "sv" => 32, "sw" => 44, "ta" => 44, "te" => 44, "tg" => 44, "th" => 44, "ti" => 44, "tk" => 44, "tl" => 44, "tn" => 44, "to" => 44, "tr" => 46, "ts" => 44, "tt" => 44, "tw" => 44, "ug" => 44, "uk" => 46, "ur" => 44, "uz" => 44, "vi" => 44, "vo" => 44, "wo" => 44, "xh" => 44, "yi" => 44, "yo" => 44, "za" => 44, "zh" => 44, "zu" => 44);
        foreach($languages as $lang => $val)
        {
            if(isset($grouping_separator_map[$lang]))
                return chr($grouping_separator_map[$lang]);
        }
        return DEFAULT_GROUPING_SEPARATOR;
    }

    function find_decimal_separator($languages)
    {
        //This map should be taken from the ouput of "ns/tools/PHPLocaleMapGenerator.java"
        $decimal_separator_map = Array("aa" => 46, "ab" => 46, "af" => 46, "am" => 46, "ar" => 46, "as" => 46, "ay" => 46, "az" => 46, "ba" => 46, "be" => 44, "bg" => 44, "bh" => 46, "bi" => 46, "bn" => 46, "bo" => 46, "br" => 46, "ca" => 44, "co" => 46, "cs" => 44, "cy" => 46, "da" => 44, "de" => 44, "dz" => 46, "el" => 44, "en" => 46, "eo" => 46, "es" => 44, "et" => 44, "eu" => 46, "fa" => 46, "fi" => 44, "fj" => 46, "fo" => 46, "fr" => 44, "fy" => 46, "ga" => 46, "gd" => 46, "gl" => 46, "gn" => 46, "gu" => 46, "ha" => 46, "he" => 46, "hi" => 46, "hr" => 44, "hu" => 44, "hy" => 46, "ia" => 46, "id" => 46, "ie" => 46, "ik" => 46, "is" => 44, "it" => 44, "iu" => 46, "ja" => 46, "jw" => 46, "ka" => 46, "kk" => 46, "kl" => 46, "km" => 46, "kn" => 46, "ko" => 46, "ks" => 46, "ku" => 46, "ky" => 46, "la" => 46, "ln" => 46, "lo" => 46, "lt" => 44, "lv" => 44, "mg" => 46, "mi" => 46, "mk" => 44, "ml" => 46, "mn" => 46, "mo" => 46, "mr" => 46, "ms" => 46, "mt" => 46, "my" => 46, "na" => 46, "ne" => 46, "nl" => 44, "no" => 44, "oc" => 46, "om" => 46, "or" => 46, "pa" => 46, "pl" => 44, "ps" => 46, "pt" => 44, "qu" => 46, "rm" => 46, "rn" => 46, "ro" => 44, "ru" => 44, "rw" => 46, "sa" => 46, "sd" => 46, "sg" => 46, "sh" => 44, "si" => 46, "sk" => 44, "sl" => 44, "sm" => 46, "sn" => 46, "so" => 46, "sq" => 44, "sr" => 44, "ss" => 46, "st" => 46, "su" => 46, "sv" => 44, "sw" => 46, "ta" => 46, "te" => 46, "tg" => 46, "th" => 46, "ti" => 46, "tk" => 46, "tl" => 46, "tn" => 46, "to" => 46, "tr" => 44, "ts" => 46, "tt" => 46, "tw" => 46, "ug" => 46, "uk" => 44, "ur" => 46, "uz" => 46, "vi" => 46, "vo" => 46, "wo" => 46, "xh" => 46, "yi" => 46, "yo" => 46, "za" => 46, "zh" => 46, "zu" => 46);

        foreach($languages as $lang => $val)
        {
            if(isset($decimal_separator_map[$lang]))
                return chr($decimal_separator_map[$lang]);
        }
        return DEFAULT_DECIMAL_SEPARATOR;
    }


    function get_grouping_separator()
    {
        if(!isset($_SESSION['grouping_separator']))
            self::set_up_locale();
        return $_SESSION['grouping_separator'];
    }

    function get_decimal_separator()
    {
        if(!isset($_SESSION['decimal_separator']))
            self::set_up_locale();
        return $_SESSION['decimal_separator'];
    }

    function get_formatted_string_from_long($val)
    {
        return number_format($val, 0, self::get_decimal_separator(), self::get_grouping_separator());
    }

    function get_formatted_string_from_double($val)
    {
        //Similar to getFormattedStringFromDouble() in "ns/db/PDUtil.java"
        $string_val = "" . number_format($val, 2, self::get_decimal_separator(), self::get_grouping_separator());
        if(preg_match("/00$/", $string_val) && (strlen($string_val) > 3))
            $string_val = substr($string_val, 0, strlen($string_val) - 3);
        return $string_val;
    }

    //Uses HTTP_USER_AGENT header to find out MSIE 6.0
    function is_client_browser_IE6()
    {
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE ([0-9]{1,}[\.0-9]{0,})/", $_SERVER['HTTP_USER_AGENT'], $version))
        {
            if(isset($version[1]) && $version[1] == "6.0")
                return true;
        }
        return false;
    }
}
?>