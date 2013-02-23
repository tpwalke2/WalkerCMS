<?php
class CachingPageStore implements IPageStore
{
 private $_cache = null;
 private $_inner_store = null;
 private $_logger = null;
 private $_cache_key_prefix = '';
 private $_cache_expiration_window = 10080;
  
 function __construct($cache, $inner_store, $logger)
 {
  $this->_cache = $cache;
  $this->_inner_store = $inner_store;
  $this->_logger = $logger;
  
  $this->_cache_key_prefix = serialize($this->_inner_store);
 }
 
 private function generate_cache_key($additional_data)
 {
  return sha1($this->_cache_key_prefix . $additional_data);
 }
 
 public function get_all_pages()
 {
  $this->_logger->debug('[WalkerCMS] Getting all pages');
  $cache_key = $this->generate_cache_key('.get_all_pages');
  
  if ($this->_cache->has($cache_key))
  {
   return $this->_cache->get($cache_key);
  }
  
  $result = $this->_inner_store->get_all_pages();
  $this->_cache->put($cache_key, $result, $this->_cache_expiration_window);
  return $result;
 }
 
 public function get_page($id)
 {
  $this->_logger->debug("[WalkerCMS] Getting page '$id'");
  $cache_key = $this->generate_cache_key(".get_page($id)");
  
  if ($this->_cache->has($cache_key))
  {
   return $this->_cache->get($cache_key);
  }
  
  $result = $this->_inner_store->get_page($id);
  $this->_cache->put($cache_key, $result, $this->_cache_expiration_window);
  
  return $result;
 }
 
 public function exists($id)
 {
  $this->_logger->debug("[WalkerCMS] Determining page existence for '$id'");
  $cache_key = $this->generate_cache_key(".exists($id)");
  
  if ($this->_cache->has($cache_key))
  {
   return $this->_cache->get($cache_key);
  }
  
  $result = $this->_inner_store->exists($id);
  $this->_cache->put($cache_key, $result, $this->_cache_expiration_window);
  
  return $result;
 }
 
 public function get_parent($of_page_id)
 {
  $this->_logger->debug("[WalkerCMS] Finding parent page for '$of_page_id'");
  $cache_key = $this->generate_cache_key(".get_parent($of_page_id)");
  
  if ($this->_cache->has($cache_key))
  {
   return $this->_cache->get($cache_key);
  }
  
  $result = $this->_inner_store->get_parent($of_page_id);
  $this->_cache->put($cache_key, $result, $this->_cache_expiration_window);
  
  return $result;
 }
 
 public function get_children($of_page_id)
 {
  $this->_logger->debug("[WalkerCMS] Finding children for '$of_page_id'");
  $cache_key = $this->generate_cache_key(".get_children($of_page_id)");
  
  if ($this->_cache->has($cache_key))
  {
   return $this->_cache->get($cache_key);
  }
  
  $result = $this->_inner_store->get_children($of_page_id);
  $this->_cache->put($cache_key, $result, $this->_cache_expiration_window);
  
  return $result;
 }
 
 public function get_all_by_properties($properties)
 {
  $this->_logger->debug('[WalkerCMS] Finding all pages by properties');
  $cache_key = $this->generate_cache_key('.get_all_by_properties('.serialize($properties).')');
  
  if ($this->_cache->has($cache_key))
  {
   return $this->_cache->get($cache_key);
  }
  
  $result = $this->_inner_store->get_all_by_properties($properties);
  $this->_cache->put($cache_key, $result, $this->_cache_expiration_window);
  
  return $result;
 }
}

/* End of file cachingpagestore.php */
/* Location: ./WalkerCMS/data/cachingpagestore.php */