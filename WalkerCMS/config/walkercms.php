<?php
$page_defaults = array(
  'parent'          => '',
  'page_title'      => '',
  'menu_title'      => '',
  'external_url'    => '',
  'override_url'    => '',
  'log_level'       => 'error',
  'show_in_nav'     => false,
  'sub_nav_on_page' => false,
  'perform_caching' => false,
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

$form_item_defaults = array(
  'description' => '',
  'type'        => 'text',
  'show_label'  => true,
  'value'       => '',
  'required'    => false,
);

$forms = array();

$walkercms_config = array(
  'version'                        => '0.9',
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

$walkercms_config['hash'] = sha1_file(path('site_specific') . 'config.php');

$pages_merger = new PagesConfigMerger();
$walkercms_config = $pages_merger->merge($pages, $page_defaults, $walkercms_config);

$forms_merger = new FormsConfigMerger();
$walkercms_config = $forms_merger->merge($forms, $form_item_defaults, $walkercms_config);

$validator = new ChainedConfigValidator(new PagesConfigValidator(), new FormsConfigValidator());
$validation_result = $validator->validate($walkercms_config);
if (!$validation_result['valid'])
{
 $plurality = (count($validation_result['errors']) == 1 ? ' was' : 's were');
 throw new ErrorException("The following error$plurality found during validation:\n" . join("\n", $validation_result['errors']));
}

return $walkercms_config;

/* End of file walkercms.php */
