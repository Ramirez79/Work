<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "common/login";
$route['404_override'] = '';

//Links handled by login controller
$route['login/do_login'] = "common/login/do_login";
$route['login/banner'] = "common/login/banner";

//Links handled by menu controller
$route['menu/gui'] = "common/menu/configuration";
$route['menu/doc'] = "common/menu/documentation";
$route['menu/dw'] = "common/menu/downloads";
$route['menu/branding'] = "common/menu/branding";
$route['menu/neoglobaldata'] = "common/menu/neoglobaldata";
$route['menu/lo\?(:any)'] = "common/menu/logout/$1";
$route['menu/er\?(:any)'] = "common/menu/error/$1";
$route['menu/neoa'] = "common/menu/neo_configuration_applet";
$route['menu/neoa?(:any)'] = "common/menu/neo_configuration_applet/$1";
$route['menu/guia'] = "common/menu/configuration_applet";
$route['menu/guia\?(:any)'] = "common/menu/configuration_applet/$1";
$route['menu/guiw\?(:any)'] = "common/menu/configuration_webstart/$1";
$route['menu/update\?(:any)'] = "common/menu/update_token/$1";
$route['guiapplet.html'] = "common/menu/neo";
$route['menu/wsp'] = "common/menu/webstart_post";
$route['menu/csdx'] = "common/menu/check_session_sdx";
$route['menu/ss\?(:any)'] = "common/menu/setup_session/$1";
$route['menu/impr_file'] = "common/menu/import_file";

//Links handled by dashboard controller
$route['menu/st'] = "dashboard/dashboard/main";
$route['menu/stc'] = "dashboard/dashboard/content";
$route['menu/st\?(:any)'] = "dashboard/dashboard/main/$1";
$route['menu/stc\?(:any)'] = "dashboard/dashboard/content/$1";
$route['menu/cc\?(:any)'] = "dashboard/dashboard/clear_cached_result/$1";
$route['menu/chart\?(:any)'] = "dashboard/dashboard/chart/$1";
$route['menu/doop'] = "dashboard/dashboard/do_operation";
$route['menu/stapp'] = "dashboard/dashboard/application";
$route['menu/stau'] = "dashboard/dashboard/application_unit";
$route['menu/coc\?(:any)'] = "dashboard/dashboard/comparative_chart/$1";
$route['menu/moncntrs\?(:any)'] = "dashboard/dashboard/counters_popup/$1";
$route['menu/savec'] = "dashboard/dashboard/save_chart";
$route['menu/delc'] = "dashboard/dashboard/delete_chart";

//Links handled by reporting controller
$route['menu/rep'] = "reporting/reporting/main";
$route['menu/rep\?(:any)'] = "reporting/reporting/main/$1";
$route['menu/repcontent'] = "reporting/reporting/content";
$route['menu/repcontent\?(:any)'] = "reporting/reporting/content/$1";
$route['menu/cntrs\?(:any)'] = "reporting/reporting/counters_popup/$1";
$route['menu/allcntr\?(:any)'] = "reporting/reporting/retrieve_all_counters/$1";
$route['menu/git\?(:any)'] = "reporting/reporting/image_toolbar/$1";
$route['menu/saver'] = "reporting/reporting/save_report";
$route['menu/delr'] = "reporting/reporting/delete_report";
$route['menu/delrfs'] = "reporting/reporting/delete_reports_folders";
$route['menu/checkr'] = "reporting/reporting/check_report_exists";
$route['menu/rds'] = "reporting/reporting/refresh_data_sources";
$route['menu/repchart\?(:any)'] = "reporting/reporting/chart/$1";
$route['menu/gim\?(:any)'] = "reporting/reporting/image_map/$1";
$route['menu/createfol'] = "reporting/reporting/create_folder";
$route['menu/mcr'] = "reporting/reporting/manage_custom_reports";
$route['menu/agc'] = "reporting/reporting/apply_global_conf";
$route['menu/expr'] = "reporting/reporting/export_reports";
$route['menu/impr'] = "reporting/reporting/import_reports";

//Links handled by pcidss/pcidss controller
$route['pcidss/([a-zA-Z_]+)\?(.+)'] = "pcidss/pcidss/$1/$2";

//Links handled by neo/topn/agee/cb home - RDX based applications
$route['rapi/(:any)'] = "common/rapi/main/$1";
$route['menu/neo'] = "common/menu/neo";
$route['menu/topn\?(:any)'] = "common/menu/topn/$1";
$route['menu/agee\?(:any)'] = "common/menu/agee/$1";
$route['menu/cb\?(:any)'] = "common/menu/cb/$1";

//Links handled by authorizer controller
$route['help/:any'] = "common/authorizer/help";
$route['support/docs/pdf/:any'] = "common/authorizer/other";
$route['support/docs/snmp/HP-Openview/:any'] = "common/authorizer/other";
$route['support/docs/snmp/WhatsUpGold/:any'] = "common/authorizer/other";
$route['api/:any'] = "common/authorizer/other";
$route['nitro.*.tgz'] = "common/authorizer/other";
$route['msn/:any'] = "common/authorizer/other";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
