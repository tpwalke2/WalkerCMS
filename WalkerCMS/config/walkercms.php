<?php
$page_defaults = array(
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
  'version'                        => '0.5',
  'site'                           => 'walkercms',
  'show_ie_warning'                => true,
  'maximum_unsupported_ie_version' => '7',
  'page_cache_expiration'          => 10080,
  'contact_page'                   => '',
  'contact_email'                  => '',
  'contact_name'                   => '',
  'admin_email'                    => '',
  'admin_name'                     => '',
  'organization_name'              => 'WalkerCMS',
  'organization_full_title'        => 'WalkerCMS',
);

require_once(path('site_specific') . 'config.php');

$merger = new ConfigMerger();
return $merger->merge($pages, $page_defaults, $walkercms_config);

/* End of file walkercms.php */
/* Location: ./WalkerCMS/config/walkercms.php */
