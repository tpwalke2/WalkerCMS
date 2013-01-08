<?php
class FirstChildPageRetriever implements IPageRetriever
{
 private $_matcher = null;
 private $_parent_retriever = null;
 private $_logger = null;
 
 function __construct($matcher, $parent_retriever, $logger_adapter)
 {
  $this->_matcher = $matcher;
  $this->_parent_retriever = $parent_retriever;
  $this->_logger = $logger_adapter;
 }
 
 public function get_page($pages, $page)
 {
  $this->_logger->debug("[WalkerCMS] Getting first child for page '{$page->get_id()}'");
  $parent = $this->_parent_retriever->get_page($pages, $page);
  
  foreach ($pages as $id=>$working_page)
  {
   if ($this->_matcher->is_match($working_page, $parent))
   {
    return $working_page;
   }
  }
  
  return null;
 }
}

/* End of file firstchildpageretriever.php */
/* Location: ./WalkerCMS/helpers/firstchildpageretriever.php */