<?php
require_once(path('app') . 'helpers/interfaces/page_retriever.php');

class FirstChildPageRetriever implements IPageRetriever
{
 private $_matcher = null;
 private $_parent_retriever = null;
 
 function __construct($matcher, $parent_retriever)
 {
  $this->_matcher = $matcher;
  $this->_parent_retriever = $parent_retriever;
 }
 
 public function get_page($pages, $working_page)
 {
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