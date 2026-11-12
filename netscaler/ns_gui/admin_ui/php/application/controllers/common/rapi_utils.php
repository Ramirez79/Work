<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class rapi_utils
{
    function populate_credentials_from_token(&$user_name, &$password)
    {
        if(!isset($_SESSION["NSAPI"]))
            return false;

        $token = $_SESSION["NSAPI"];
        $token_split_index = ceil(strlen($token)/2);
        $user_name = substr($token, 0, $token_split_index);
        $password = substr($token, $token_split_index, $token_split_index);

        return true;
    }

    // Makes a SFTP connection, authorizes user for SFTP access & returns false is failed
    // Else returns the created sftp handle (which then can be used to download files etc)
    function get_sftp(&$response)
    {
        if(!self::populate_credentials_from_token($user_name, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return false;
        }

        include("Net/SFTP.php");
        @$net_sftp = new Net_SFTP(LOCAL_HOST);
        if(!@$net_sftp->login($user_name, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return false;
        }

        return $net_sftp;
    }

    function populate_systemlog_file(&$response, $dir, $object_type, $file_content_list, $regex_array, $modification_time = false)
    {
        if(self::get_sftp($response) === false)
            return;

        if(($dir_handle = @opendir($dir)) === false)
        {
            $response["message"] = "Error opening directory";
            return;
        }

        $files = array();
        while(($file_name = readdir($dir_handle)) !== false)
        {
            $file_full_path = $dir . $file_name;
            $matches = array();
            if($file_name == "." || $file_name == ".." || !is_file($file_full_path))
                continue;

            for ($i = 0; $i < count($regex_array); ++$i)
            {
                $regex = $regex_array[$i];

                if ($regex != null && preg_match($regex, $file_name, $matches))
                {
                    $file = array("name" => ((count($matches) > 1) ? $matches[1] : $file_name));

                    if($modification_time !== false)
                    {
                        $file[$modification_time] = date ("D, d M Y H:i:s", filemtime($file_full_path));
                    }

                    $files[$file_content_list[$i]][] = $file;
                }
            }
        }

        closedir($dir_handle);
        $response[$object_type] = $files;
    }
    
    function populate_file_listing(&$response, $dir, $object_type, $regex = null, $modification_time = false, $timezone_offset = 0)
    {
        if(self::get_sftp($response) === false)
            return;

        if(($dir_handle = @opendir($dir)) === false)
        {
            $response["message"] = "Error opening directory";
            return;
        }

        $files = array();

        while(($file_name = readdir($dir_handle)) !== false)
        {
            $file_full_path = $dir . $file_name;
            $matches = array();

            if($file_name == "." || $file_name == ".." ||
                ($regex != null && !preg_match($regex, $file_name, $matches)) ||
                !is_file($file_full_path))
                continue;

            $file = array("name" => ((count($matches) > 1) ? $matches[1] : $file_name));
            if($modification_time !== false)
                $file[$modification_time] = filemtime($file_full_path) + $timezone_offset;
            $files[] = $file;
        }

        closedir($dir_handle);
        $response[$object_type] = $files;
    }

    function get_file_contents(&$response, $file_path, &$start, &$prev, &$next)
    {
        if(($net_ssh = self::get_ssh($ssh_response)) !== false)
        {
            $is_compressed = false;

            if (preg_match("/^(.*).gz$/", $file_path, $matches))
            {
                $is_compressed = true;
            }

            $cat_command = "/bin/cat ";
            if ($is_compressed)
                $cat_command = "/usr/bin/zcat ";
            
            $file_size = filesize($file_path);
            $syslog_lines = array();
            $prev = -1;
            $next = -1;

            $net_ssh->setTimeout(1);
            $net_ssh->write("shell\n");
            $net_ssh->read('/[\z]/');
            
            if ($file_size > 500000)
            {
                $tail_counter = $start + 2000;
                $total_lines_command = $cat_command . rapi_utils::sanitize_shell_arg($file_path) . " | wc -l" . "\r\n";
                
                $net_ssh->write($total_lines_command);
                $total_lines = $net_ssh->read('/[\z]/');

                if (strcmp($total_lines, $total_lines_command) == 0)
                {
                    $total_lines = "";
                    set_error_handler("exception_error_handler");

                    while (empty($total_lines))
                    {
                        try
                        {
                            $total_lines = $net_ssh->read('/[\z]/');
                        }
                        catch(ErrorException $exception)
                        {
                            $net_ssh->reset();
                            restore_error_handler();
                            $response["message"] = "Could not process the file. File size is too large.";
                            return false;
                        }
                    }

                    restore_error_handler();
                    $total_lines = $total_lines . $net_ssh->read('/[\z]/');
                    $number_of_lines = (int) trim($total_lines);
                }
                else
                {
                    $lines_array = explode("\r\n", $total_lines);
                    $number_of_lines = (int) trim($lines_array[1]);
                }

                if ($start == 0)
                {
                    $tail_command = $cat_command . rapi_utils::sanitize_shell_arg($file_path) . " | tail -n " . rapi_utils::sanitize_shell_arg($tail_counter) . "\r\n";
                    $prev = -1;
                    $next = ($number_of_lines < 2000)? -1 : $tail_counter;
                }
                else if ($tail_counter > $number_of_lines)
                {
                    $remaining_lines = $number_of_lines - $start;
                    $tail_command = $cat_command . rapi_utils::sanitize_shell_arg($file_path) . " | tail -n " . rapi_utils::sanitize_shell_arg($tail_counter) . " | head -n " . rapi_utils::sanitize_shell_arg($remaining_lines) . "\r\n";
                    $prev = $start - 2000;
                    $next = -1;
                }
                else
                {
                    $tail_command = $cat_command . rapi_utils::sanitize_shell_arg($file_path) . " | tail -n " . rapi_utils::sanitize_shell_arg($tail_counter) . " | head -n 2000" . "\r\n";
                    $prev = $tail_counter - (2 * 2000);
                    $next = $tail_counter;
                }
            }
            else
            {
                $tail_command = $cat_command . rapi_utils::sanitize_shell_arg($file_path) . " | tail +0" . "\r\n";
            }
            
            $net_ssh->write($tail_command);
            $lines = $net_ssh->read('/[\z]/');

            if (strcmp($lines, $tail_command) == 0)
            {
                $lines = "";
                set_error_handler("exception_error_handler");

                while (empty($lines))
                {
                    try
                    {
                        $lines = $net_ssh->read('/[\z]/');
                    }
                    catch(ErrorException $exception)
                    {
                        $net_ssh->reset();
                        restore_error_handler();
                        $response["message"] = "Could not process the file. File size is too large.";
                        return false;
                    }
                }

                restore_error_handler();
                $lines = $lines . $net_ssh->read('/[\z]/');
            }
            
            $output_array = explode("\r\n", $lines);
            $number_of_elements = count($output_array);
            $last_cli_message = $output_array[$number_of_elements - 1];

            foreach($output_array as $key => $line)
            {
                $noise_message = false;

                if ((strcmp(trim($line), "root@ns#") == 0) || (strcmp(trim($line), trim($tail_command)) == 0) || (strcmp(trim($line), trim($last_cli_message)) == 0))
                    $noise_message = true;
                if (strlen(trim($line)) && trim($line) !== "Done" && $key !== ($number_of_elements - 1) && !$noise_message)
                    $syslog_lines[] = $line;
            }

            $net_ssh->disconnect();
            
            return $syslog_lines;
        }
        else
        {    
            return false;
        }    
    }
    
    function does_args_match($object, $args)
    {
        if(!is_array($args) || count($args) == 0)
            return true;
        if(!is_array($object))
            return false;

        foreach($args as $key => $value)
        {
            $is_array = is_array($value);

            if(!isset($object[$key]) ||
                ($is_array && !in_array($object[$key], $value)) ||
                (!$is_array && $object[$key] != $value))
                return false;
        }

        return true;
    }

    function does_filter_match($object, $filter, $use_pattern_match = true)
    {
        if(!is_array($filter) || count($filter) == 0)
            return true;
        if(!is_array($object))
            return false;

        foreach($filter as $key => $value)
        {
            if(is_array($value))
                $value = implode(';', $value);
            if(!isset($object[$key]))
                return false;
            if($use_pattern_match === true && !preg_match($value, $object[$key]))
                return false;
            else if($use_pattern_match === false &&  strpos($object[$key], $value) === false)
                return false;
        }

        return true;
    }
    
    function get_ssh(&$response, $timeout = 10)
    {
        if(!self::populate_credentials_from_token($user_name, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return false;
        }

        require_once("Net/SSH2.php");
        $net_ssh = new Net_SSH2(LOCAL_HOST, 22, $timeout);

        if(!$net_ssh->login($user_name, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return false;
        }

        return $net_ssh;
    }

    function sanitize_response($data)
    {
        if(is_numeric($data) || is_string($data))
            return htmlentities(trim($data));

        $sanitized_data = array();

        foreach ($data as $key => $value)
        {   
            if(is_numeric($value) || is_string($value))
                $sanitized_data[htmlentities(trim($key))] = htmlentities(trim($value));
            else
                $sanitized_data[htmlentities(trim($key))] = rapi_utils::sanitize_response($value);
        }

        return $sanitized_data;    
    }

    function sanitize_shell_arg($data)
    {
        $blacklist = array("`", "$", "|", ";","&", ">", "<", "@", "#", "%", "?", "^", "~", "!", "..", "[", "]");

        for ($i = 0; $i < count($blacklist); ++$i)
        {
            if (strpos($data, $blacklist[$i]) !== false)
            {
                return NULL;                
            }
        }

        $sanitized_data = escapeshellarg($data);
        // TODO - Add Security team suggested logic here

        return $sanitized_data;    
    }

    function download($file_path, $file_name) 
    {     
        if(!empty($file_path)) 
        { 
            $fileInfo = pathinfo($file_path);

            if(!isset($file_name))
                $file_name  = $fileInfo['basename']; 

            $fileExtnesion   = $fileInfo['extension']; 
            $default_contentType = "application/octet-stream"; 
            $content_types_list = rapi_utils::get_mime_types(); 

            if (array_key_exists($fileExtnesion, $content_types_list))  
            { 
                $contentType = $content_types_list[$fileExtnesion]; 
            } 
            else 
            { 
                $contentType =  $default_contentType; 
            } 
            
            if(file_exists($file_path)) 
            { 
                $size = filesize($file_path); 
                $offset = 0; 
                $length = $size; 

                set_time_limit(0); // To support very large files                

                // Partial Download facility
                // <TODO>

                // Usual headers for Download
                header("Content-Disposition: attachment;filename=\"".$file_name."\""); 	// filename="x xx" - include space
                header('Content-Type: '.$contentType); 
                header("Accept-Ranges: bytes"); 
                header("Pragma: public"); 
                header("Expires: -1"); 
                header("Cache-Control: no-cache"); 
                header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0"); 
                header("Content-Length: ".filesize($file_path)); 

                $chunksize = 8 * (1024 * 1024); // 8MB (Highest possible fread length) 

                if ($size > $chunksize) 
                { 
                    $handle = fopen($file_path, 'rb'); 
                    $buffer = ''; 
                    while (!feof($handle) && (connection_status() === CONNECTION_NORMAL))  
                    { 
                        $buffer = fread($handle, $chunksize); 
                        print $buffer; 
                        ob_flush(); 
                        flush(); 
                    } 

                    if(connection_status() !== CONNECTION_NORMAL) 
                    { 
                        echo "Connection aborted"; 
                    } 
                    
                    fclose($handle); 
                } 
                else  
                { 
                    ob_clean(); 
                    flush(); 
                    readfile($file_path); 
                } 
            } 
            else 
            { 
                echo 'File does not exist!'; 
            } 
        } 
        else 
        { 
            echo 'There is no file to download!'; 
        } 
    }     

    function get_mime_types() 
    { 
        // Just add any required MIME type if you are going to download something not listed here.
        $mime_types = array("323" => "text/h323", 
                            "acx" => "application/internet-property-stream", 
                            "ai" => "application/postscript", 
                            "aif" => "audio/x-aiff", 
                            "aifc" => "audio/x-aiff", 
                            "aiff" => "audio/x-aiff", 
                            "asf" => "video/x-ms-asf", 
                            "asr" => "video/x-ms-asf", 
                            "asx" => "video/x-ms-asf", 
                            "au" => "audio/basic", 
                            "avi" => "video/x-msvideo", 
                            "axs" => "application/olescript", 
                            "bas" => "text/plain", 
                            "bcpio" => "application/x-bcpio", 
                            "bin" => "application/octet-stream", 
                            "bmp" => "image/bmp", 
                            "c" => "text/plain", 
                            "cat" => "application/vnd.ms-pkiseccat", 
                            "cdf" => "application/x-cdf", 
                            "cer" => "application/x-x509-ca-cert", 
                            "class" => "application/octet-stream", 
                            "clp" => "application/x-msclip", 
                            "cmx" => "image/x-cmx", 
                            "cod" => "image/cis-cod", 
                            "cpio" => "application/x-cpio", 
                            "crd" => "application/x-mscardfile", 
                            "crl" => "application/pkix-crl", 
                            "crt" => "application/x-x509-ca-cert", 
                            "csh" => "application/x-csh", 
                            "css" => "text/css", 
                            "dcr" => "application/x-director", 
                            "der" => "application/x-x509-ca-cert", 
                            "dir" => "application/x-director", 
                            "dll" => "application/x-msdownload", 
                            "dms" => "application/octet-stream", 
                            "doc" => "application/msword", 
                            "dot" => "application/msword", 
                            "dvi" => "application/x-dvi", 
                            "dxr" => "application/x-director", 
                            "eps" => "application/postscript", 
                            "etx" => "text/x-setext", 
                            "evy" => "application/envoy", 
                            "exe" => "application/octet-stream", 
                            "fif" => "application/fractals", 
                            "flr" => "x-world/x-vrml", 
                            "gif" => "image/gif", 
                            "gtar" => "application/x-gtar", 
                            "gz" => "application/x-gzip", 
                            "h" => "text/plain", 
                            "hdf" => "application/x-hdf", 
                            "hlp" => "application/winhlp", 
                            "hqx" => "application/mac-binhex40", 
                            "hta" => "application/hta", 
                            "htc" => "text/x-component", 
                            "htm" => "text/html", 
                            "html" => "text/html", 
                            "htt" => "text/webviewhtml", 
                            "ico" => "image/x-icon", 
                            "ief" => "image/ief", 
                            "iii" => "application/x-iphone", 
                            "ins" => "application/x-internet-signup", 
                            "isp" => "application/x-internet-signup", 
                            "jfif" => "image/pipeg", 
                            "jpe" => "image/jpeg", 
                            "jpeg" => "image/jpeg", 
                            "jpg" => "image/jpeg", 
                            "js" => "application/x-javascript", 
                            "latex" => "application/x-latex", 
                            "lha" => "application/octet-stream", 
                            "lsf" => "video/x-la-asf", 
                            "lsx" => "video/x-la-asf", 
                            "lzh" => "application/octet-stream", 
                            "m13" => "application/x-msmediaview", 
                            "m14" => "application/x-msmediaview", 
                            "m3u" => "audio/x-mpegurl", 
                            "man" => "application/x-troff-man", 
                            "mdb" => "application/x-msaccess", 
                            "me" => "application/x-troff-me", 
                            "mht" => "message/rfc822", 
                            "mhtml" => "message/rfc822", 
                            "mid" => "audio/mid", 
                            "mny" => "application/x-msmoney", 
                            "mov" => "video/quicktime", 
                            "movie" => "video/x-sgi-movie", 
                            "mp2" => "video/mpeg", 
                            "mp3" => "audio/mpeg", 
                            "mpa" => "video/mpeg", 
                            "mpe" => "video/mpeg", 
                            "mpeg" => "video/mpeg", 
                            "mpg" => "video/mpeg", 
                            "mpp" => "application/vnd.ms-project", 
                            "mpv2" => "video/mpeg", 
                            "ms" => "application/x-troff-ms", 
                            "mvb" => "application/x-msmediaview", 
                            "nws" => "message/rfc822", 
                            "oda" => "application/oda", 
                            "p10" => "application/pkcs10", 
                            "p12" => "application/x-pkcs12", 
                            "p7b" => "application/x-pkcs7-certificates", 
                            "p7c" => "application/x-pkcs7-mime", 
                            "p7m" => "application/x-pkcs7-mime", 
                            "p7r" => "application/x-pkcs7-certreqresp", 
                            "p7s" => "application/x-pkcs7-signature", 
                            "pbm" => "image/x-portable-bitmap", 
                            "pdf" => "application/pdf", 
                            "pfx" => "application/x-pkcs12", 
                            "pgm" => "image/x-portable-graymap", 
                            "pko" => "application/ynd.ms-pkipko", 
                            "pma" => "application/x-perfmon", 
                            "pmc" => "application/x-perfmon", 
                            "pml" => "application/x-perfmon", 
                            "pmr" => "application/x-perfmon", 
                            "pmw" => "application/x-perfmon", 
                            "pnm" => "image/x-portable-anymap", 
                            "pot" => "application/vnd.ms-powerpoint", 
                            "ppm" => "image/x-portable-pixmap", 
                            "pps" => "application/vnd.ms-powerpoint", 
                            "ppt" => "application/vnd.ms-powerpoint", 
                            "prf" => "application/pics-rules", 
                            "ps" => "application/postscript", 
                            "pub" => "application/x-mspublisher", 
                            "qt" => "video/quicktime", 
                            "ra" => "audio/x-pn-realaudio", 
                            "ram" => "audio/x-pn-realaudio", 
                            "ras" => "image/x-cmu-raster", 
                            "rgb" => "image/x-rgb", 
                            "rmi" => "audio/mid", 
                            "roff" => "application/x-troff", 
                            "rtf" => "application/rtf", 
                            "rtx" => "text/richtext", 
                            "scd" => "application/x-msschedule", 
                            "sct" => "text/scriptlet", 
                            "setpay" => "application/set-payment-initiation", 
                            "setreg" => "application/set-registration-initiation", 
                            "sh" => "application/x-sh", 
                            "shar" => "application/x-shar", 
                            "sit" => "application/x-stuffit", 
                            "snd" => "audio/basic", 
                            "spc" => "application/x-pkcs7-certificates", 
                            "spl" => "application/futuresplash", 
                            "src" => "application/x-wais-source", 
                            "sst" => "application/vnd.ms-pkicertstore", 
                            "stl" => "application/vnd.ms-pkistl", 
                            "stm" => "text/html", 
                            "svg" => "image/svg+xml", 
                            "sv4cpio" => "application/x-sv4cpio", 
                            "sv4crc" => "application/x-sv4crc", 
                            "t" => "application/x-troff", 
                            "tar" => "application/x-tar", 
                            "tcl" => "application/x-tcl", 
                            "tex" => "application/x-tex", 
                            "texi" => "application/x-texinfo", 
                            "texinfo" => "application/x-texinfo", 
                            "tgz" => "application/x-compressed", 
                            "tif" => "image/tiff", 
                            "tiff" => "image/tiff", 
                            "tr" => "application/x-troff", 
                            "trm" => "application/x-msterminal", 
                            "tsv" => "text/tab-separated-values", 
                            "txt" => "text/plain", 
                            "uls" => "text/iuls", 
                            "ustar" => "application/x-ustar", 
                            "vcf" => "text/x-vcard", 
                            "vrml" => "x-world/x-vrml", 
                            "wav" => "audio/x-wav", 
                            "wcm" => "application/vnd.ms-works", 
                            "wdb" => "application/vnd.ms-works", 
                            "wks" => "application/vnd.ms-works", 
                            "wmf" => "application/x-msmetafile", 
                            "wps" => "application/vnd.ms-works", 
                            "wri" => "application/x-mswrite", 
                            "wrl" => "x-world/x-vrml", 
                            "wrz" => "x-world/x-vrml", 
                            "xaf" => "x-world/x-vrml", 
                            "xbm" => "image/x-xbitmap", 
                            "xla" => "application/vnd.ms-excel", 
                            "xlc" => "application/vnd.ms-excel", 
                            "xlm" => "application/vnd.ms-excel", 
                            "xls" => "application/vnd.ms-excel", 
                            "xlt" => "application/vnd.ms-excel", 
                            "xlw" => "application/vnd.ms-excel", 
                            "xof" => "x-world/x-vrml", 
                            "xpm" => "image/x-xpixmap", 
                            "xwd" => "image/x-xwindowdump", 
                            "z" => "application/x-compress", 
                            "rar" => "application/x-rar-compressed", 
                            "zip" => "application/zip"); 
        return $mime_types;                     
    } 
}
?>
