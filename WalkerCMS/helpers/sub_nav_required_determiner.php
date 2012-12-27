<?php
require_once(path('app') . 'helpers/interfaces/required_determiner.php');

class SubNavRequiredDeterminer implements IRequiredDeterminer
{
 private $_parent_retriever = null;
 
 function __construct($parent_retriever)
 {
  $this->_parent_retriever = $parent_retriever;
 }
 
 public function is_required($pages, $page)
 {
  if ($page == null) { throw new InvalidArgumentException('Page is null'); }
  if ($page->get_parent() != '')
  {
   return $this->is_required($pages, $this->_parent_retriever->get_page($pages, $page));
  }
  if ($page->has_custom_sub_nav()) { return true; }
  return $page->get_sub_nav_on_page();
 }
}

/* End of file sub_nav_required_determiner.php */
/* Location: /sub_nav_required_determiner.php */