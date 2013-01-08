<?php
class ContentSourcePageRetriever implements IPageRetriever
{
 private $_first_child_retriever = null;
 private $_logger = null;
 
 function __construct($first_child_retriever, $logger)
 {
  $this->_first_child_retriever = $first_child_retriever;
  $this->_logger = $logger;
 }
 
 public function get_page($pages, $page)
 {
  $this->_logger->debug("[WalkerCMS] Getting content source page for page '{$page->get_id()}'");
  if ($page->get_sub_nav_on_page() && !$page->has_content()) { return $this->_first_child_retriever->get_page($pages, $page); }
  return $page;
 }
}

/* End of file contentsourcepageretriever.php */
/* Location: ./WalkerCMS/helpers/contentsourcepageretriever.php */