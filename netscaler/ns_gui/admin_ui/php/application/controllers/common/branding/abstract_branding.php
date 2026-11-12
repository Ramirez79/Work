<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
  abstract class abstract_branding
  {
      abstract protected function get_brand_login_css();
      abstract protected function get_brand_name();
      abstract protected function get_brand_logo();
      abstract protected function get_brand_link();
      abstract protected function get_copyright_link();
      abstract protected function get_copyright_name();
      abstract protected function get_title();
      abstract protected function show_help();

      public function get_applinks()
      {
         //Keep this in sync with branding.js.txt
          return $app_links = array(
                  array("link" => "/menu/st", "text" => "Dashboard" , "value" => "st"),
                  array("link" => "/menu/neo", "text" => "Configuration", "value" => "neo"),
                  array("link" => "/menu/rep", "text" => "Reporting", "value" => "rep")
          );
      }

      public function get_infolinks()
      {
          return array(
                    array("link" => "/menu/doc", "text" => "Documentation", "value" => "doc"),
                    array("link" => "/menu/dw", "text" => "Downloads", "value" => "dw")
          );
      }

      public function get_login_page_links()
      {
        $links = array(array("link" => "/menu/neo", "text" => "Default", "value" => "def", "default" => true));
        $links = array_merge($links , $this->get_applinks());
        $links = array_merge($links , $this->get_infolinks());

        return $links;
      }
}
?>
