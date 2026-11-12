use lib "$ENV{PortalRoot}portal/modules";
use Apache2::porting;
#Common modules
use Apache2::compat;
use ModPerl::Registry ();
use CGI (); 
use Apache::Session::File;

#Remove all cache files under /var/tmp/netscaler/portal/templates
system("/bin/rm -f /var/tmp/netscaler/portal/templates/*");

1;
