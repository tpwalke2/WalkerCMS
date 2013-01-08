<?php
class TemplateDataGenerator implements ITemplateDataGenerator
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
 
 public function generate_data($context)
 {
  $current_page = $context->get_current_page();
  $page_title = $current_page->get_page_title();
  $organization_name = $this->_config->get('walkercms.organization_name');
  $organization_full_title = $this->_config->get('walkercms.organization_full_title', $organization_name);
  return array(
    'site' => $this->_config->get('walkercms.site'),
    'page_id' => $current_page->get_id(),
    'page_title' => htmlentities($organization_name . ($page_title == '' ? '' : ": $page_title")),
    'organization_full_title' => htmlentities($organization_full_title),
    'organization_name' => htmlentities($organization_name),
    'organization_slogan' => htmlentities($this->_config->get('walkercms.organization_slogan', '')),
    'site_description' => htmlentities($this->_config->get('walkercms.description')),
    'site_keywords' => htmlentities($this->_config->get('walkercms.keywords')),
    'has_page_specific_html_header' => $current_page->has_custom_html_header(),
    'has_page_specific_stylesheet' => $current_page->has_custom_css(),
    'has_page_specific_javascript' => $current_page->has_custom_js(),
    'has_contact_form' => ($this->_config->get('walkercms.contact_page') == $context->get_current_page()->get_id()),
    'show_ie_warning' => $this->_config->get('walkercms.show_ie_warning'),
    'minimum_ie_version' => $this->_config->get('walkercms.minimum_ie_version'),
    'has_secondary_content' => $current_page->has_secondary_content(),
    'has_page_specific_header' => $current_page->has_custom_page_header(),
    'content_page_id' => $context->get_content_source_page()->get_id(),
    'has_sub_nav' => $this->_sub_nav_required_determiner->is_required($context->get_pages(), $current_page),
    'has_page_specific_footer' => $current_page->has_custom_footer(),
  );
 }
}

/* End of file templatedatagenerator.php */
/* Location: ./WalkerCMS/helpers/templatedatagenerator.php */