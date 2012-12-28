<?php
require_once(path('app') . 'helpers/interfaces/pages_retriever.php');

class ConfigPagesRetriever implements IPagesRetriever
{
 private $_page_factory = null;
 private $_config_adapter = null;
 private $_logger = null;
 
 function __construct($page_factory, $config_adapter, $logger_adapter)
 {
  $this->_page_factory = $page_factory;
  $this->_config_adapter = $config_adapter;
  $this->_logger = $logger_adapter;
 }
 
 public function get_pages()
 {
  $this->_logger->debug('[WalkerCMS] Retrieving pages from config');
  $result = array();

  foreach ($this->_config_adapter->get('walkercms.pages') as $id => $page_definition)
  {
   $result[$id] = $this->_page_factory->create($page_definition);
  }

  return $result;
 }
}

/* End of file config_pages_retriever.php */
/* Location: ./WalkerCMS/helpers/config_pages_retriever.php */