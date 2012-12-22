<?php

class PageModel
{
 private $_inner_page;

 function __construct($page)
 {
  $page['id'] = trim($page['id']);
  if ($page['id'] == '') {
   throw new InvalidArgumentException('Page ID must be non-empty.');
  }
  $this->_inner_page = $page;
 }

 public function get_id()
 {
  return $this->_inner_page['id'];
 }

 private function has_file_type($type, $ext = '')
 {
  $ext = trim($ext);
  if ($ext != '') {
   $ext = ".$ext";
  }
  return File::exists(path('site_specific') . "$type/{$this->get_id()}$ext");
 }

 public function has_content()
 {
  return $this->has_file_type('content');
 }

 public function has_secondary_content()
 {
  return $this->has_file_type('secondarycontent');
 }

 public function has_custom_nav()
 {
  return $this->has_file_type('nav');
 }

 public function has_custom_sub_nav()
 {
  return $this->has_file_type('subnav');
 }

 public function has_custom_js()
 {
  return $this->has_file_type('scripts', 'js');
 }

 public function has_custom_css()
 {
  return $this->has_file_type('styles', 'css');
 }

 public function has_custom_html_header()
 {
  return $this->has_file_type('htmlheaders');
 }

 public function has_custom_page_header()
 {
  return $this->has_file_type('headers');
 }

 public function has_custom_footer()
 {
  return $this->has_file_type('footers');
 }

 public function get_page_title()
 {
  return $this->_inner_page['page_title'];
 }

 public function get_menu_title()
 {
  return $this->_inner_page['menu_title'];
 }

 public function get_show_in_nav()
 {
  return $this->_inner_page['show_in_nav'];
 }

 public function get_external_url()
 {
  return $this->_inner_page['external_url'];
 }

 public function get_override_url()
 {
  return $this->_inner_page['override_url'];
 }

 public function get_parent()
 {
  return $this->_inner_page['parent'];
 }

 public function get_sub_nav_on_page()
 {
  return $this->_inner_page['sub_nav_on_page'];
 }

 public function get_perform_caching()
 {
  return $this->_inner_page['perform_caching'];
 }
}

/* End of file page_model.php */
/* Location: ./WalkerCMS/entities/page_model.php */