<?php
class TopMostSubNavParentRetriever implements IPageRetriever
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function get_page($pages, $page)
 {
  $this->_logger->debug("[WalkerCMS] Retrieving top most parent with sub nav for page '{$page->get_id()}'");
  if ($page->get_sub_nav_on_page()) { return $page; }
  if ($page->get_parent() == '') { return $page; }
  return $this->get_page($pages, $pages[$page->get_parent()]);
 }
}

/* End of file topmostsubnavparentretriever.php */
/* Location: ./WalkerCMS/helpers/topmostsubnavparentretriever.php */