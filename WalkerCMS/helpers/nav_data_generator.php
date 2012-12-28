<?php
require_once(path('app') . 'helpers/interfaces/data_generator.php');

class NavDataGenerator implements IDataGenerator
{
 private $_nav_item_converter = null;
 private $_matcher = null;
 private $_parent_retriever = null;
 private $_config_adapter = null;
 private $_is_primary_nav = true;
 private $_logger = null;
 
 function __construct($nav_item_converter, $matcher, $parent_retriever, $config_adapter, $is_primary_nav, $logger_adapter)
 {
  $this->_nav_item_converter = $nav_item_converter;
  $this->_matcher = $matcher;
  $this->_parent_retriever = $parent_retriever;
  $this->_config_adapter = $config_adapter;
  $this->_is_primary_nav = $is_primary_nav;
  $this->_logger = $logger_adapter;
 }

 public function generate_data($pages, $current_page, $content_source_page)
 {
  $this->_logger->debug("[WalkerCMS] Generating nav data for page '{$current_page->get_id()}'");
  return array(
    'nav_id' => ($this->_is_primary_nav ? 'nav' : 'subNav'),
    'is_primary_nav' => $this->_is_primary_nav,
    'nav_items' => $this->convert_nav_items($pages, $current_page),
    'organization_name' => $this->_config_adapter->get('walkercms.organization_name'),
  );
 }

 private function convert_nav_items($pages, $current_page)
 {
  $result = array();

  foreach ($pages as $id=>$page)
  {
   if ($this->_matcher->is_match($page, $this->_parent_retriever->get_page($pages, $current_page)))
   {
    $result[] = $this->_nav_item_converter->convert($pages, $page, $current_page);
   }
  }

  return $result;
 }
}

/* End of file nav_data_generator.php */
/* Location: ./WalkerCMS/helpers/nav_data_generator.php */