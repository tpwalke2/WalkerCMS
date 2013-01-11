<?php
class PageGenerator implements IPageGenerator
{
 private $_template_data_generator = null;
 private $_html_header_data_generator = null;
 private $_page_header_data_generator = null;
 private $_content_data_generator = null;
 private $_nav_data_generator = null;
 private $_sub_nav_data_generator = null;
 private $_custom_sub_nav_data_generator = null;
 private $_secondary_content_data_generator = null;
 private $_footer_data_generator = null;
 private $_content_source_page_retriever = null;
 private $_logger = null;
 
 function __construct($template_data_generator,
                      $html_header_data_generator,
                      $page_header_data_generator,
                      $content_data_generator,
                      $nav_data_generator,
                      $sub_nav_data_generator,
                      $custom_sub_nav_data_generator,
                      $secondary_content_data_generator,
                      $footer_data_generator,
                      $content_source_page_retriever,
                      $logger)
 {
  $this->_template_data_generator = $template_data_generator;
  $this->_html_header_data_generator = $html_header_data_generator;
  $this->_page_header_data_generator = $page_header_data_generator;
  $this->_content_data_generator = $content_data_generator;
  $this->_nav_data_generator = $nav_data_generator;
  $this->_sub_nav_data_generator = $sub_nav_data_generator;
  $this->_custom_sub_nav_data_generator = $custom_sub_nav_data_generator;
  $this->_secondary_content_data_generator = $secondary_content_data_generator;
  $this->_footer_data_generator = $footer_data_generator;
  $this->_content_source_page_retriever = $content_source_page_retriever;
  $this->_logger = $logger;
 }
 
 public function generate_page($context)
 {
  $current_page = $context->get_current_page();
  $context->set_content_source_page($this->_content_source_page_retriever->get_page($context->get_pages(), $current_page));
  $this->_logger->debug("[WalkerCMS] Content source page ID: {$context->get_content_source_page()->get_id()}");
  
  $common_data = $this->_template_data_generator->generate_data($context);
  $nav_data = $this->_nav_data_generator->generate_data($current_page, $context);
  $nav_data['nav_items'] = View::make('partials.nav', $nav_data);
  
  // TODO: no need to show sub nav if page does not have sub nav
  $sub_nav_data = array();
  $sub_nav_view = 'partials.nav_template';
  if ($current_page->has_custom_sub_nav())
  {
   $this->_logger->debug("[WalkerCMS] Custom sub nav for page '{$current_page->get_id()}'");
   $sub_nav_data = $this->_custom_sub_nav_data_generator->generate_data($current_page, $context);
   $sub_nav_view = 'partials.page_inclusion';
  } else {
   $sub_nav_data = $this->_sub_nav_data_generator->generate_data($current_page, $context);
   $sub_nav_data['nav_items'] = View::make('partials.nav', $sub_nav_data);
  }
  
  $content_data = $this->_content_data_generator->generate_data($context->get_content_source_page(), $context);
  $secondary_content_data = $this->_secondary_content_data_generator->generate_data($current_page, $context);
  $page_specific_html_header_data = $this->_html_header_data_generator->generate_data($current_page, $context);
  $page_specific_header_data = $this->_page_header_data_generator->generate_data($current_page, $context);
  $page_specific_footer_data = $this->_footer_data_generator->generate_data($current_page, $context);
  
  return View::make('layouts.common', $common_data)
               ->nest('page_specific_html_header', 'partials.page_inclusion', $page_specific_html_header_data)
               ->nest('nav', 'partials.nav_template', $nav_data)
               ->nest('sub_nav', $sub_nav_view, $sub_nav_data)
               ->nest('page_content', 'partials.page_inclusion', $content_data)
               ->nest('secondary_content', 'partials.page_inclusion', $secondary_content_data)
               ->nest('page_specific_header', 'partials.page_inclusion', $page_specific_header_data)
               ->nest('page_specific_footer', 'partials.page_inclusion', $page_specific_footer_data);
 }
}

/* End of file pagegenerator.php */
/* Location: ./WalkerCMS/helpers/pagegenerator.php */