<?php
require_once(path('app') . 'helpers/interfaces/data_generator.php');

class PageSpecificInclusionDataGenerator implements IDataGenerator
{
 private $_inclusion_type = null;
 
 function __construct($inclusion_type)
 {
  $this->_inclusion_type = $inclusion_type;
 }
 
 public function generate_data($pages, $current_page, $current_source_page)
 {
  return array('inclusion_type' => $this->_inclusion_type,
               'page_id' => $current_page->get_id());
 }
}

/* End of file page_specific_inclusion_data_generator.php */
/* Location: ./WalkerCMS/helpers/page_specific_inclusion_data_generator.php */