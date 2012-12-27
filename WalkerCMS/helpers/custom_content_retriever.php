<?php
require_once(path('app') . 'helpers/interfaces/custom_content_retriever.php');

class CustomContentRetriever implements ICustomContentRetriever
{
 private $_data_generator = null;
 private $_view_adapter = null;

 function __construct($data_generator, $view_adapter)
 {
  $this->_data_generator = $data_generator;
  $this->_view_adapter = $view_adapter;
 }

 public function retrieve_content($pages, $page)
 {
  return $this->_view_adapter->generate_view('partials.page_inclusion', $this->_data_generator->generate_data($pages, $page, $page));
 }
}

/* End of file custom_content_retriever.php */
/* Location: ./WalkerCMS/helpers/custom_content_retriever.php */