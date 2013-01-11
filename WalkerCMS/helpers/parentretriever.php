<?php
class ParentRetriever implements IPageRetriever
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function get_page($pages, $page)
 {
  $this->_logger->debug("[WalkerCMS] Retrieving parent for page '{$page->get_id()}'");
  if ($page->get_parent() == '') { return null; }
  if (!isset($pages[$page->get_parent()])) { return null; }
  return $pages[$page->get_parent()];
 }
}

/* End of file parentretriever.php */
/* Location: ./WalkerCMS/helpers/parentretriever.php */