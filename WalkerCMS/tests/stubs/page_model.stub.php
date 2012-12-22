<?php
class PageModel_Stub
{
 private $_options = null;

 function __construct($options)
 {
  if (!isset($options['id'])) {
   throw new InvalidArgumentException("Missing 'id' element");
  }
  $this->_options = $options;
 }

 public function set_option($key, $value)
 {
  $this->_options[$key] = $value;
 }

 public function get_id()
 {
  return $this->_options['id'];
 }

 private function has_option_value($option)
 {
  if (isset($this->_options[$option])) {
   return $this->_options[$option];
  }
  return false;
 }

 public function has_content()
 {
  return $this->has_option_value('content');
 }

 public function has_secondary_content()
 {
  return $this->has_option_value('secondarycontent');
 }

 public function has_custom_nav()
 {
  return $this->has_option_value('nav');
 }

 public function has_custom_sub_nav()
 {
  return $this->has_option_value('subnav');
 }

 public function has_custom_js()
 {
  return $this->has_option_value('scripts');
 }

 public function has_custom_css()
 {
  return $this->has_option_value('styles');
 }

 public function has_custom_html_header()
 {
  return $this->has_option_value('htmlheaders');
 }

 public function has_custom_page_header()
 {
  return $this->has_option_value('headers');
 }

 public function has_custom_footer()
 {
  return $this->has_option_value('footers');
 }

 public function get_page_title()
 {
  return $this->has_option_value('page_title');
 }

 public function get_menu_title()
 {
  return $this->has_option_value('menu_title');
 }

 public function get_show_in_nav()
 {
  return $this->has_option_value('show_in_nav');
 }

 public function get_external_url()
 {
  return $this->has_option_value('external_url');
 }

 public function get_override_url()
 {
  return $this->has_option_value('override_url');
 }

 public function get_parent()
 {
  return $this->has_option_value('parent');
 }

 public function get_sub_nav_on_page()
 {
  return $this->has_option_value('sub_nav_on_page');
 }

 public function get_perform_caching()
 {
  return $this->has_option_value('perform_caching');
 }
}

/* End of file page_model.stub.php */
/* Location: ./WalkerCMS/tests/stubs/page_model.stub.php */