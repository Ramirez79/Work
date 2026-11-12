<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

require_once(APPPATH. "controllers/common/abstract_controller.php");

class auth_utils extends abstract_controller
{
    function ldap_retrieve_attributes($data)
    {
	if(isset($data["ipaddress"]))
	{
	    $ldaphost = $data["ipaddress"];
	}
	else
	{
	    $ldaphost = $data["servername"];
	}
	
        $ldappass = $data["password"];
        $base_dn = $data["basedn"];
        $bind_dn = $data["binddn"];
        $ldapport = $data["port"];
	$sectype = $data["sectype"];
        
        $retrieved_attributes = array();
        
        // Establishing the ldap connection
	if($sectype == "SSL")
	{
	    $ldapconn = ldap_connect("ldaps://".$ldaphost.":".$ldapport);
	}
	else
	{
	    $ldapconn = ldap_connect("ldap://".$ldaphost.":".$ldapport);
	}
        
        if ($ldapconn)
        {
	    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
	    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	    
	    if ($sectype == "TLS" && !ldap_start_tls($ldapconn))
	    {
		$this->print_error_for_api_request("Unable to start TLS.", -1);
                return false;
	    }
	    
            //Binding to ldap server
            $ldapbind = ldap_bind($ldapconn, $bind_dn, $ldappass);
            
            //Checks for successful binding
            if($ldapbind)
            {
                $sr = ldap_search($ldapconn, $base_dn, "(objectClass=*)");
               
                $entry = ldap_first_entry($ldapconn, $sr);
               
                while($entry)
                {
                    $attrs = ldap_get_attributes($ldapconn, $entry);
                    
                    for ($item=0; $item < $attrs["count"]; $item++)
                    {
                       if (!in_array($attrs[$item], $retrieved_attributes))
                       {
                            array_push($retrieved_attributes, $attrs[$item]);
                       }
                    }
                    
                    $entry = ldap_next_entry($ldapconn, $entry);
                }
                
                sort($retrieved_attributes);
            }
            else
            {
                $this->print_error_for_api_request("Connection to LDAP server could not be established.\nThere may be some network problem or incorrect connection parameters.", 49);
                return false;
            }
        }
        else
        {
            $this->print_error_for_api_request("Connection to LDAP server could not be established.\nThere may be some network problem or incorrect connection parameters.", 49);
            return false;
        }
        
        $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'retrieved_attributes' => (object) array('ldaploginname' => $retrieved_attributes, 'groupattrname' => $retrieved_attributes, 'subattributename' => $retrieved_attributes, 'ssonameattribute' => $retrieved_attributes, 'groupsearchsubattribute' => $retrieved_attributes, 'groupnameidentifier' => $retrieved_attributes, 'groupsearchattribute' => $retrieved_attributes));      
        return $output; 
    }
}

?>
