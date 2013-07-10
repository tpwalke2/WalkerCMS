<?php
class ContextFactory implements IContextFactory
{
 private $_page_store = null;
 private $_page_id_validator = null;
 private $_content_source_page_retriever = null;
 private $_session = null;
 private $_logger = null;
 
 function __construct(
   $page_store,
   $page_id_validator,
   $content_source_page_retriever,
   $session,
   $logger)
 {
  $this->_page_store = $page_store;
  $this->_page_id_validator = $page_id_validator;
  $this->_content_source_page_retriever = $content_source_page_retriever;
  $this->_session = $session;
  $this->_logger = $logger;
 }
 
 public function create($page_id)
 {
  $this->_logger->debug("[WalkerCMS] Getting context for page id '$page_id'");
  $result = $this->_session->get('context');
  
  if ($result === null) {
   $this->_logger->debug('[WalkerCMS] Creating new context');
   $result = new AppContext();
  }
  else
  {
   $this->_logger->debug('[WalkerCMS] Context passed from session');
  }
  
  $result->set_page_store($this->_page_store);
  
  $page_id = $this->_page_id_validator->get_validated_page_id($this->_page_store->get_all_pages(), $page_id);
  $this->_logger->debug("[WalkerCMS] Validated page ID: $page_id");
  $result->set_current_page_id($page_id);
  
  $result->set_content_source_page($this->_content_source_page_retriever->get_page($this->_page_store->get_all_pages(), $this->_page_store->get_page($page_id)));
  $this->_logger->debug("[WalkerCMS] Content source page ID: {$result->get_content_source_page()->get_id()}");
  
  $result->set_site(new SiteModel());

  $this->_session->put('context', $result);
  
  return $result;
 }
}

/* End of file contextfactory.php */
/* Location: ./WalkerCMS/helpers/contextfactory.php */