<?php
require_once(path('app') . 'helpers/interfaces/data_generator.php');

class CustomNavContentDataGenerator implements IDataGenerator
{
 private $_nav_type = '';
 private $_logger = null;

 function __construct($nav_type, $logger_adapter)
 {
  $this->_nav_type = $nav_type;
  $this->_logger = $logger_adapter;
 }

 public function generate_data($pages, $current_page, $content_source_page)
 {
  $this->_logger->debug("[WalkerCMS] Generating custom nav content data for page '{$current_page->get_id()}'");
  return array(
    'inclusion_type' => $this->_nav_type,
    'page_id'        => $current_page->get_id()
  );
 }
}

/* End of file custom_nav_content_data_generator.php */
/* Location: ./WalkerCMS/helpers/custom_nav_content_data_generator.php */