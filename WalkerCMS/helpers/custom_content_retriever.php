<?php
require_once(path('app') . 'helpers/interfaces/custom_content_retriever.php');

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

 public function retrieve_content($pages, $page)
 {
  $this->_logger->debug("[WalkerCMS] Getting inclusion view for page '{$page->get_id()}'");
  return $this->_view_adapter->generate_view('partials.page_inclusion', $this->_data_generator->generate_data($pages, $page, $page));
 }
}

/* End of file custom_content_retriever.php */
/* Location: ./WalkerCMS/helpers/custom_content_retriever.php */