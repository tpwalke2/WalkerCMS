<?php
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

 public function generate_data($working_page, $context)
 {
  $this->_logger->debug("[WalkerCMS] Generating nav data for page '{$working_page->get_id()}'");
  return array(
    'nav_id' => ($this->_is_primary_nav ? 'nav' : 'subNav'),
    'is_primary_nav' => $this->_is_primary_nav,
    'nav_items' => $this->convert_nav_items($context),
    'organization_name' => $this->_config_adapter->get('walkercms.organization_name'),
  );
 }

 private function convert_nav_items($context)
 {
  $result = array();

  foreach ($context->get_pages() as $id=>$page)
  {
   if ($this->_matcher->is_match($page, $this->_parent_retriever->get_page($context->get_pages(), $context->get_current_page())))
   {
    $result[] = $this->_nav_item_converter->convert($page, $context);
   }
  }

  return $result;
 }
}

/* End of file navdatagenerator.php */
/* Location: ./WalkerCMS/helpers/navdatagenerator.php */