<?php
class PageChildMatcher implements IPageMatcher
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function is_match($page, $parent)
 {
  $this->_logger->debug("[WalkerCMS] Determining if page '{$parent->get_id()}' is the parent of page '{$page->get_id()}'");
  return ($page->get_parent() == $parent->get_id());
 }
}

/* End of file pagechildmatcher.php */
/* Location: ./WalkerCMS/helpers/pagechildmatcher.php */