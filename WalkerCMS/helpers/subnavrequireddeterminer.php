<?php
class SubNavRequiredDeterminer implements IRequiredDeterminer
{
 private $_parent_retriever = null;
 private $_logger = null;
 
 function __construct($parent_retriever, $logger)
 {
  $this->_parent_retriever = $parent_retriever;
  $this->_logger = $logger;
 }
 
 public function is_required($pages, $page)
 {
  if ($page == null) { throw new InvalidArgumentException('Page is null'); }
  $this->_logger->debug("[WalkerCMS] Determining if sub nav is required for page '{$page->get_id()}'");
  if ($page->get_parent() != '')
  {
   return $this->is_required($pages, $this->_parent_retriever->get_page($pages, $page));
  }
  if ($page->has_custom_sub_nav()) { return true; }
  return $page->get_sub_nav_on_page();
 }
}

/* End of file subnavrequireddeterminer.php */
/* Location: /subnavrequireddeterminer.php */