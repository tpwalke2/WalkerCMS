<?php
require_once(path('app') . 'helpers/interfaces/data_generator.php');

class TemplateDataGenerator implements IDataGenerator
{
 private $_custom_content_retriever = null;
 private $_sub_nav_required_determiner = null;
 private $_config = null;
 private $_logger = null;
 
 function __construct($sub_nav_required_determiner, $config_adapter, $logger_adapter)
 {
  $this->_sub_nav_required_determiner = $sub_nav_required_determiner;
  $this->_config = $config_adapter;
  $this->_logger = $logger_adapter;
 }
 
 public function generate_data($pages, $current_page, $content_source_page)
 {
  $page_id = $current_page->get_id();
  $page_title = $current_page->get_page_title();
  $organization_name = $this->_config->get('walkercms.organization_name');
  $organization_full_title = $this->_config->get('walkercms.organization_full_title', $organization_name);
  return array(
    'site' => $this->_config->get('walkercms.site'),
    'page_id' => $page_id,
    'page_title' => htmlentities($organization_name . ($page_title == '' ? '' : ": $page_title")),
    'organization_full_title' => htmlentities($organization_full_title),
    'organization_name' => htmlentities($organization_name),
    'organization_slogan' => htmlentities($this->_config->get('walkercms.organization_slogan', '')),
    'site_description' => htmlentities($this->_config->get('walkercms.description')),
    'site_keywords' => htmlentities($this->_config->get('walkercms.keywords')),
    'has_page_specific_html_header' => $current_page->has_custom_html_header(),
    'has_page_specific_stylesheet' => $current_page->has_custom_css(),
    'has_page_specific_javascript' => $current_page->has_custom_js(),
    'show_ie_warning' => $this->_config->get('walkercms.show_ie_warning'),
    'minimum_ie_version' => $this->_config->get('walkercms.minimum_ie_version'),
    'has_secondary_content' => $current_page->has_secondary_content(),
    'has_page_specific_header' => $current_page->has_custom_page_header(),
    'content_page_id' => $content_source_page->get_id(),
    'has_sub_nav' => $this->_sub_nav_required_determiner->is_required($pages, $current_page),
    'has_page_specific_footer' => $current_page->has_custom_footer()
  );
 }
}

/* End of file template_data_generator.php */
/* Location: ./WalkerCMS/helpers/template_data_generator.php */