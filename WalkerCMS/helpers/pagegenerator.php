<?php
use Laravel\Blade;

class PageGenerator implements IPageGenerator
{
 private $_template_data_generator = null;
 private $_nav_data_generator = null;
 private $_sub_nav_data_generator = null;
 private $_custom_sub_nav_data_generator = null;
 private $_generators = null;
 private $_view = null;
 private $_logger = null;
 
 function __construct($template_data_generator,
                      $site_specific_html_header_data_generator,
                      $page_specific_html_header_data_generator,
                      $page_header_data_generator,
                      $content_data_generator,
                      $nav_data_generator,
                      $sub_nav_data_generator,
                      $custom_sub_nav_data_generator,
                      $secondary_content_data_generator,
                      $footer_data_generator,
                      $view,
                      $logger)
 {
  $this->_template_data_generator = $template_data_generator;
  $this->_generators = array(
    'site_specific_html_header' => $site_specific_html_header_data_generator,
    'page_specific_html_header' => $page_specific_html_header_data_generator,
    'page_content'              => $content_data_generator,
    'secondary_content'         => $secondary_content_data_generator,
    'page_specific_header'      => $page_header_data_generator,
    'page_specific_footer'      => $footer_data_generator
  );
  $this->_nav_data_generator = $nav_data_generator;
  $this->_sub_nav_data_generator = $sub_nav_data_generator;
  $this->_custom_sub_nav_data_generator = $custom_sub_nav_data_generator;
  $this->_view = $view;
  $this->_logger = $logger;
 }
 
 public function generate_page($context)
 {
  $current_page = $context->get_current_page();
  
  $result = $this->_view->generate_view('layouts.common', $this->_template_data_generator->generate_data($context));
  
  foreach ($this->_generators as $key=>$generator)
  {
   $result[$key] = $this->_view->generate_view('partials.page_inclusion', $generator->generate_data($current_page, $context));
  }
  
  $result['nav'] = $this->_view->generate_view('partials.page_inclusion', $this->generate_full_nav_data($current_page, $context));
  
  $sub_nav_data = array();
  $sub_nav_view = 'partials.page_inclusion';
  if ($current_page->has_custom_sub_nav())
  {
   $this->_logger->debug("[WalkerCMS] Custom sub nav for page '{$current_page->get_id()}'");
   $sub_nav_data = $this->_custom_sub_nav_data_generator->generate_data($current_page, $context);
  } else {
   $sub_nav_data = $this->_sub_nav_data_generator->generate_data($current_page, $context);
   $sub_nav_data['nav_items'] = $this->_view->generate_view('partials.nav', $sub_nav_data);
  }
  $result['sub_nav'] = $this->_view->generate_view($sub_nav_view, $sub_nav_data);
  
  return $result;
 }
 
 private function generate_full_nav_data($current_page, $context)
 {
  $nav_data = $this->_nav_data_generator->generate_data($current_page, $context);
  $nav_data['nav_items'] = $this->_view->generate_view('partials.nav', $nav_data);
  $nav_data['inclusion_type'] = 'template';
  $nav_data['inclusion_file'] = 'nav_template.php';
  return $nav_data;
 }
}

/* End of file pagegenerator.php */
/* Location: ./WalkerCMS/helpers/pagegenerator.php */