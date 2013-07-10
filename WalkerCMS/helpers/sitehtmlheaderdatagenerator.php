<?php
class SiteHTMLHeaderDataGenerator implements IDataGenerator
{
 private $_logger = null;
 
 function __construct($logger_adapter)
 {
  $this->_logger = $logger_adapter;
 }
 
 public function generate_data($working_page, $context)
 {
  $this->_logger->debug('[WalkerCMS] Generating site HTML header data');
  return array('inclusion_type' => 'htmlheaders',
               'page_id' => 'site');
 }
}

/* End of file sitehtmlheaderdatagenerator.php */
/* Location: ./WalkerCMS/helpers/sitehtmlheaderdatagenerator.php */