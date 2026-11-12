<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
//Close ns_content div
print "</div>\n";
print "<div id=\"ns_footer\" class=\"" . (!isset($no_footer_link) ? "ns_footer_parent" : ""). "\">\n";
if(!isset($no_footer_link))
{
    $footer_link = get_branding()->get_copyright_link();
    $footer_name = get_branding()->get_copyright_name();
    print "<a class=\"citrix_login_logo\">Citrix Systems, Inc.</a>";
    print "<div class=\"ns_footer_center\">Copyright(&copy;) <a href= '" .$footer_link ."' target=\"PRODUCT_LINK\">" . $footer_name ."</a> All rights reserved.</div>\n";
}
?>
</div>
</body>
</html>
