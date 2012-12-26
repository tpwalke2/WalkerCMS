<?php
require_once(path('app') . 'helpers/interfaces/data_generator.php');

class CustomNavContentDataGenerator implements IDataGenerator
{
 private $_nav_type = '';

 function __construct($nav_type)
 {
  $this->_nav_type = $nav_type;
 }

 public function generate_data($pages, $page)
 {
  return array(
    'inclusion_type' => $this->_nav_type,
    'page_id'        => $page->get_id()
  );
 }
}

/* End of file custom_nav_content_data_generator.php */
/* Location: ./WalkerCMS/helpers/custom_nav_content_data_generator.php */