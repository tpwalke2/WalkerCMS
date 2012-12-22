<?php
class NavItemConverter_Stub
{
 private $_nav_item = false;

 function __construct($nav_item)
 {
  $this->_nav_item = $nav_item;
 }

 public function convert($pages, $page, $current_page)
 {
  return $this->_nav_item;
 }
}

/* End of file nav_item_converter.stub.php */
/* Location: ./WalkerCMS/tests/stubs/nav_item_converter.stub.php */
