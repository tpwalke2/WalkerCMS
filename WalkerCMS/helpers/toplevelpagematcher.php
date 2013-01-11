<?php
class TopLevelPageMatcher implements IPageMatcher
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function is_match($page, $parent)
 {
  $this->_logger->debug("[WalkerCMS] Determining if page '{$page->get_id()}' is visible in the primary nav");
  if (!$page->get_show_in_nav()) { return false; }
  return ($page->get_parent() == '');
 }
}

/* End of file toplevelpagematcher.php */
/* Location: ./WalkerCMS/helpers/toplevelpagematcher.php */