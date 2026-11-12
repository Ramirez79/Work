package NetScaler::Portal::Config;
use vars qw(%c);

%c = (
  bookmark_dir => "/var/vpn/bookmark/",
  template_dir => "/var/vpn/templates:$ENV{PortalRoot}portal/templates" ,
  base_template_dir => "$ENV{PortalRoot}portal/templates" ,
  adminTemplate_dir => "$ENV{PortalRoot}portal/admin/templates" ,
  theme_dir => "/var/vpn/themes/" ,
  template_compile_dir => "/var/tmp" ,
  homepage => "$ENV{portalLoc}homepage.html"
);
1;