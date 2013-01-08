<?php
class CustomContentRetriever implements ICustomContentRetriever
{
 private $_data_generator = null;
 private $_view_adapter = null;
 private $_logger = null;

 function __construct($data_generator, $view_adapter, $logger_adapter)
 {
  $this->_data_generator = $data_generator;
  $this->_view_adapter = $view_adapter;
  $this->_logger = $logger_adapter;
 }

 public function retrieve_content($working_page, $context)
 {
  $this->_logger->debug("[WalkerCMS] Getting inclusion view for page '{$working_page->get_id()}'");
  return $this->_view_adapter->generate_view('partials.page_inclusion', $this->_data_generator->generate_data($working_page, $context));
 }
}

/* End of file customcontentretriever.php */
/* Location: ./WalkerCMS/helpers/customcontentretriever.php */