<?php
class CustomNavContentRetriever_Stub
{
 private $_custom_nav_content = '';

 function __construct($custom_nav_content = '')
 {
  $this->_custom_nav_content = $custom_nav_content;
 }

 public function retrieve($pages, $page)
 {
  return $this->_custom_nav_content;
 }
}

/* End of file custom_nav_content.stub.php */
/* Location: ./WalkerCMS/tests/stubs/custom_nav_content.stub.php */
