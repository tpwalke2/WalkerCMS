<?php
class ConfigPageStore implements IPageStore
{
 private $_factory = null;
 private $_config = null;
 private $_logger = null;
 
 function __construct($factory, $config, $logger)
 {
  $this->_factory = $factory;
  $this->_config = $config;
  $this->_logger = $logger;
 }
 
 public function get_all_pages()
 {
  $this->_logger->debug('[WalkerCMS] Getting all pages');
  $pages_config = $this->_config->get('walkercms.pages');
  
  $result = array();
  if ($pages_config === null) { return $result; }
  
  foreach ($pages_config as $page_id=>$page_data)
  {
   $result[$page_id] = $this->_factory->create($page_data);
  }
  
  return $result;
 }
 
 public function get_page($id)
 {
  $this->_logger->debug("[WalkerCMS] Getting page '$id'");
  $pages_config = $this->_config->get('walkercms.pages');
  
  if (isset($pages_config[$id])) { return $this->_factory->create($pages_config[$id]); }
  return null;
 }
 
 public function exists($id)
 {
  $this->_logger->debug("[WalkerCMS] Determining page existence for '$id'");
  $pages_config = $this->_config->get('walkercms.pages');
  
  return isset($pages_config[$id]);
 }
 
 public function get_parent($of_page_id)
 {
  $this->_logger->debug("[WalkerCMS] Finding parent page for '$of_page_id'");
  $child = $this->get_page($of_page_id);
  if ($child === null) { return null; }
  if ($child->get_parent() === '') { return null; }
  return $this->get_page($child->get_parent());
 }
 
 public function get_children($of_page_id)
 {
  $this->_logger->debug("[WalkerCMS] Finding children for '$of_page_id'");
  $pages = $this->get_all_pages();
  
  $result = array();
  
  foreach ($pages as $page_id=>$page)
  {
   if ($page->get_parent() == $of_page_id)
   {
    $result[] = $page;
   }
  }
  
  return $result;
 }
 
 public function get_all_by_properties($properties)
 {
  $this->_logger->debug('[WalkerCMS] Finding all pages by properties');
  $pages_config = $this->_config->get('walkercms.pages');
  $result = array();
  if ($pages_config === null) { return $result; }
  if ($properties === null) { return $result; }
   
  foreach ($pages_config as $page_id=>$page_definition)
  {
   $page_matches = true;
   
   foreach ($properties as $key=>$val)
   {
    if (!isset($page_definition[$key]) || ($page_definition[$key] != $val)) { $page_matches = false; }
   }
   
   if ($page_matches) { $result[] = $this->_factory->create($page_definition); }
  }
  
  return $result;
 }
}
/* End of file configpagestore.php */
/* Location: ./WalkerCMS/data/configpagestore.php */