<?php
class ContextFactory implements IContextFactory
{
 private $_pages_retriever = null;
 private $_page_id_validator = null;
 private $_session = null;
 private $_logger = null;
 
 function __construct($pages_retriever, $page_id_validator, $session, $logger)
 {
  $this->_pages_retriever = $pages_retriever;
  $this->_page_id_validator = $page_id_validator;
  $this->_session = $session;
  $this->_logger = $logger;
 }
 
 public function create($page_id)
 {
  $this->_logger->debug("[WalkerCMS] Generating context for page id '$page_id'");
  $result = $this->_session->get('context');
  if ($result === null) {
   $this->_logger->debug('[WalkerCMS] Creating new context');
   $result = new AppContext();
  }
  else
  {
   $this->_session->forget('context');
   $this->_logger->debug('[WalkerCMS] Context passed from session');
   $this->_logger->debug("[WalkerCMS] $result");
  }
  
  // PHP 5.5 function array dereference will make this variable unnecessary
  $pages = $this->_pages_retriever->get_pages();
  $result->set_pages($pages);
  
  $page_id = $this->_page_id_validator->get_validated_page_id($pages, $page_id);
  $this->_logger->debug("[WalkerCMS] Validated page ID: $page_id");
  $result->set_current_page($pages[$page_id]);
  
  return $result;
 }
}

/* End of file contextfactory.php */
/* Location: ./WalkerCMS/helpers/contextfactory.php */