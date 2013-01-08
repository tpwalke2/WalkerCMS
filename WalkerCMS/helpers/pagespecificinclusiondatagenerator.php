<?php
class PageSpecificInclusionDataGenerator implements IDataGenerator
{
 private $_inclusion_type = null;
 private $_logger = null;
 
 function __construct($inclusion_type, $logger_adapter)
 {
  $this->_inclusion_type = $inclusion_type;
  $this->_logger = $logger_adapter;
 }
 
 public function generate_data($working_page, $context)
 {
  $this->_logger->debug("[WalkerCMS] Generating '{$this->_inclusion_type}' type data for page '{$working_page->get_id()}'");
  return array('inclusion_type' => $this->_inclusion_type,
               'page_id' => $working_page->get_id());
 }
}

/* End of file pagespecificinclusiondatagenerator.php */
/* Location: ./WalkerCMS/helpers/pagespecificinclusiondatagenerator.php */