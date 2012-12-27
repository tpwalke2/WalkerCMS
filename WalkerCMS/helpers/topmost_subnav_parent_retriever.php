<?php
require_once(path('app') . 'helpers/interfaces/page_retriever.php');

class TopMostSubNavParentRetriever implements IPageRetriever
{
 public function get_page($pages, $working_page)
 {
  if ($working_page->get_sub_nav_on_page()) { return $working_page; }
  if ($working_page->get_parent() == '') { return $working_page; }
  return $this->get_page($pages, $pages[$working_page->get_parent()]);
 }
}

/* End of file topmost_subnav_parent_retriever.php */
/* Location: ./WalkerCMS/helpers/topmost_subnav_parent_retriever.php */