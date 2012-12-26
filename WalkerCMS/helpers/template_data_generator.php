<?php
require_once(path('app') . 'helpers/interfaces/data_generator.php');

class TemplateDataGenerator implements IDataGenerator
{
 private $_config_adapter = null;
 
 function __construct($config_adapter)
 {
  $this->_config_adapter = $config_adapter;
 }
 
 public function generate_data($pages, $page)
 {
  $page_id = $page->get_id();
  $page_title = $page->get_page_title();
  $organization_name = $this->_config_adapter->get('walkercms.organization_name');
  $organization_full_title = $this->_config_adapter->get('walkercms.organization_full_title', $organization_name);
  return array(
    'site' => $this->_config_adapter->get('walkercms.site'),
    'page_id' => $page_id,
    'page_title' => $organization_name . ($page_title == '' ? '' : ": $page_title"),
    'organization_full_title' => $organization_full_title,
    'organization_name' => $organization_name,
    'organization_slogan' => $this->_config_adapter->get('walkercms.organization_slogan', ''),
    'site_description' => $this->_config_adapter->get('walkercms.description'),
    'site_keywords' => $this->_config_adapter->get('walkercms.keywords'),
    'has_page_specific_html_header' => $page->has_custom_html_header(),
    'has_page_specific_stylesheet' => $page->has_custom_css(),
    'has_page_specific_javascript' => $page->has_custom_js(),
    'show_ie_warning' => $this->_config_adapter->get('walkercms.show_ie_warning'),
    'minimum_ie_version' => $this->_config_adapter->get('walkercms.minimum_ie_version'),
    'has_secondary_content' => $page->has_secondary_content(),
    'has_page_specific_header' => $page->has_custom_page_header(),
    'content_page_id' => $page_id, // TODO: pages that have sub Nav but no content
    'has_sub_nav' => $page->has_custom_sub_nav(),
    'has_page_specific_footer' => $page->has_custom_footer()
  );
 }
}

/* End of file template_data_generator.php */
/* Location: ./WalkerCMS/helpers/template_data_generator.php */