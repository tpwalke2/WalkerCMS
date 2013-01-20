<?php
class Main_Controller extends Base_Controller
{
 private $_context_factory = null;
 private $_page_generator = null;
 private $_config = null;
 private $_cache = null;
 
 function __construct(
   $context_factory,
   $page_generator,
   $config,
   $cache,
   $response,
   $logger)
 {
  parent::__construct($response, $logger);
  $this->_context_factory = $context_factory;
  $this->_page_generator = $page_generator;
  $this->_config = $config;
  $this->_cache = $cache;
 }

 public function action_page($page_id = 'home')
 {
  $this->_logger->debug("[WalkerCMS] Requested page: $page_id");

  $context = $this->_context_factory->create($page_id);

  $cache_key = 'view_' . $context->get_current_page()->get_id();
  if ($this->perform_caching($context->get_current_page()) && $this->_cache->has($cache_key))
  {
   $this->_logger->debug('[WalkerCMS] Performing caching and the view has been cached.');
   return $this->_cache->get($cache_key);
  }

  $generated_page = $this->_page_generator->generate_page($context);

  if ($this->perform_caching($context->get_current_page()))
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
}

/* End of file main.php */
/* Location: ./WalkerCMS/controllers/main.php */