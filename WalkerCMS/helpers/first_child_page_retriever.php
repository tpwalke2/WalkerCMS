<?php
require_once(path('app') . 'helpers/interfaces/page_retriever.php');

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
 
 public function get_page($pages, $working_page)
 {
  $this->_logger->debug("[WalkerCMS] Getting first child for page '{$working_page->get_id()}'");
  $parent = $this->_parent_retriever->get_page($pages, $working_page);
  
  foreach ($pages as $id=>$page)
  {
   if ($this->_matcher->is_match($page, $parent))
   {
    return $page;
   }
  }
  
  return null;
 }
}

/* End of file first_child_page_retriever.php */
/* Location: ./WalkerCMS/helpers/first_child_page_retriever.php */