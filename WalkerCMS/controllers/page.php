<?php
class Page_Controller extends Base_Controller
{
 private $_pages_retriever = null;
 private $_page_id_validator = null;
 private $_template_data_generator = null;
 private $_nav_data_generator = null;
 private $_sub_nav_data_generator = null;
 private $_custom_sub_nav_data_generator = null;
 private $_content_source_page_retriever = null;
 private $_config = null;
 private $_cache = null;
 private $_logger = null;
 
 function __construct($pages_retriever,
                      $page_id_validator,
                      $template_data_generator,
                      $nav_data_generator,
                      $sub_nav_data_generator,
                      $custom_sub_nav_data_generator,
                      $content_source_page_retriever,
                      $config,
                      $cache,
                      $logger)
 {
  parent::__construct();
  $this->_pages_retriever = $pages_retriever;
  $this->_page_id_validator = $page_id_validator;
  $this->_template_data_generator = $template_data_generator;
  $this->_nav_data_generator = $nav_data_generator;
  $this->_sub_nav_data_generator = $sub_nav_data_generator;
  $this->_custom_sub_nav_data_generator = $custom_sub_nav_data_generator;
  $this->_content_source_page_retriever = $content_source_page_retriever;
  $this->_config = $config;
  $this->_cache = $cache;
  $this->_logger = $logger;
 }
 
 public function action_page($page_id = 'home')
 {
  $this->_logger->debug("[WalkerCMS] Beginning generation of page: $page_id");
  $pages = $this->_pages_retriever->get_pages();
  $page_id = $this->_page_id_validator->get_validated_page_id($pages, $page_id);
  $this->_logger->debug("[WalkerCMS] Validated page ID: $page_id");
  $current_page = $pages[$page_id];
  
  $cache_key = 'view_' . $current_page->get_id();
  if ($this->perform_caching($current_page) && $this->_cache->has($cache_key))
  {
   $this->_logger->debug('[WalkerCMS] Performing caching and the view has been cached.');
   return $this->_cache->get($cache_key);
  }
  
  $generated_page = $this->generate_page($pages, $current_page);

  if ($this->perform_caching($current_page))
  {
   $this->_logger->debug('[WalkerCMS] Putting the view into the cache.');
   $this->_cache->put($cache_key, $generated_page, $this->_config->get('walkercms.page_cache_expiration'));
  }
  
  return $generated_page;
 }
 
 private function perform_caching($current_page)
 {
  return ($current_page->get_perform_caching() && ($current_page->get_id() != $this->_config->get('walkercms.contact_page')));
 }
 
 private function generate_page($pages, $current_page)
 {
  $content_source_page = $this->_content_source_page_retriever->get_page($pages, $current_page);
  $this->_logger->debug("[WalkerCMS] Content source page ID: {$content_source_page->get_id()}");
  
  $common_data = $this->_template_data_generator->generate_data($pages, $current_page, $content_source_page);
  $nav_data = $this->_nav_data_generator->generate_data($pages, $current_page, $content_source_page);
  
  $sub_nav_data = array();
  $sub_nav_view = 'partials.nav';
  if ($current_page->has_custom_sub_nav())
  {
   $this->_logger->debug("[WalkerCMS] Custom sub nav for page '{$current_page->get_id()}'");
   $sub_nav_data = $this->_custom_sub_nav_data_generator->generate_data($pages, $current_page, $content_source_page);
   $sub_nav_view = 'partials.page_inclusion';
  } else {
   $sub_nav_data = $this->_sub_nav_data_generator->generate_data($pages, $current_page, $content_source_page);
  }
  $this->logger->debug("[WalkerCMS] Subnav view is '$sub_nav_view'");
  
  $content_data = array(
    'page_id' => $content_source_page->get_id(),
    'inclusion_type' => 'content',
  );
  $secondary_content_data = array(
    'page_id' => $current_page->get_id(),
    'inclusion_type' => 'secondarycontent',
  );
  $page_specific_html_header_data = array(
    'page_id' => $current_page->get_id(),
    'inclusion_type' => 'htmlheaders',
  );
  $page_specific_header_data = array(
    'page_id' => $current_page->get_id(),
    'inclusion_type' => 'headers',
  );
  $page_specific_footer_data = array(
    'page_id' => $current_page->get_id(),
    'inclusion_type' => 'footers',
  );
  
  return View::make('layouts.common', $common_data)
               ->nest('page_specific_html_header', 'partials.page_inclusion', $page_specific_html_header_data)
               ->nest('nav', 'partials.nav', $nav_data)
               ->nest('sub_nav', $sub_nav_view, $sub_nav_data)
               ->nest('page_content', 'partials.page_inclusion', $content_data)
               ->nest('secondary_content', 'partials.page_inclusion', $secondary_content_data)
               ->nest('page_specific_header', 'partials.page_inclusion', $page_specific_header_data)
               ->nest('page_specific_footer', 'partials.page_inclusion', $page_specific_footer_data);
 }
}

/* End of file page.php */
/* Location: ./WalkerCMS/controllers/page.php */