<?php
class NavDataGenerator
{
 private $_nav_item_converter = null;
 private $_is_primary_nav = true;
 private $_matcher = null;
 private $_parent_retriever = null;

 function __construct($nav_item_converter, $matcher, $parent_retriever, $is_primary_nav)
 {
  $this->_nav_item_converter = $nav_item_converter;
  $this->_matcher = $matcher;
  $this->_parent_retriever = $parent_retriever;
  $this->_is_primary_nav = $is_primary_nav;
 }

 public function generate_data($pages, $current_page)
 {
  $organization_name = Config::get('walkercms.organization_name');

  return array(
    'nav_id' => ($this->_is_primary_nav ? 'nav' : 'subNav'),
    'is_primary_nav' => $this->_is_primary_nav,
    'nav_items' => $this->convert_nav_items($pages, $current_page),
    'organization_name' => $organization_name,
  );
 }

 private function convert_nav_items($pages, $current_page)
 {
  $result = array();

  foreach ($pages as $id=>$page)
  {
   if ($this->_matcher->is_match($page, $this->_parent_retriever->get_parent($pages, $page)))
   {
    $result[] = $this->_nav_item_converter->convert($pages, $page, $current_page);
   }
  }

  return $result;
 }
}

/* End of file nav_data_generator.php */
/* Location: ./WalkerCMS/helpers/nav_data_generator.php */