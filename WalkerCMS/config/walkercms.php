<?php

function merge_configs($pages, $pageDefaults, &$config) {
 foreach ($pages as $pageID=>$page) {
  $config['pages'][$pageID] = array();
  $config['pages'][$pageID]['id'] = $pageID;

  foreach ($pageDefaults as $key=>$val) {
   $config['pages'][$pageID][$key] = $val;
  }

  foreach ($page as $key=>$val) {
   $config['pages'][$pageID][$key] = $val;
  }
 }
}

$pageDefaults = array(
  'parent'          => '',
  'page_title'      => '',
  'menu_title'      => '',
  'external_url'    => '',
  'override_url'    => '',
  'show_in_nav'     => false,
  'sub_nav_on_page' => false,
  'perform_caching' => false
);

$pages = array(
  '403' => array(
    'page_title' => 'Unauthorized Access',
    'parent'     => 'home'
  ),
  '404' => array(
    'page_title' => 'Page Not Found',
    'parent' => 'home'
  ),
  '500' => array(
    'page_title' => 'Server Error',
    'parent' => 'home'
  )
);

$walkercms_config = array(
  'version'                 => '0.5',
  'site'                    => 'walkercms',
  'show_ie_warning'         => true,
  'minimum_ie_version'      => '7',
  'page_cache_expiration'   => 10080,
  'contact_page'            => '',
  'contact_email'           => '',
  'contact_name'            => '',
  'admin_email'             => 'tpwalke2@gmail.com',
  'admin_name'              => 'Tom Walker',
  'organization_name'       => 'Walker Software Consulting',
  'organization_full_title' => 'Walker Software Consulting',
);

require_once(path('site_specific') . 'config.php');

merge_configs($pages, $pageDefaults, $walkercms_config);

return $walkercms_config;

/* End of file walkercms.php */
/* Location: ./WalkerCMS/config/walkercms.php */
