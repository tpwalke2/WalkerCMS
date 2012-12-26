<?php
require_once(path('app') . 'helpers/interfaces/parent_retriever.php');

class TopMostSubNavParentRetriever implements IParentRetriever
{
 public function get_parent($pages, $page)
 {
  if ($page->get_sub_nav_on_page()) { return $page; }
  if ($page->get_parent() == '') { return $page; }
  return $this->get_parent($pages, $pages[$page->get_parent()]);
 }
}

/* End of file topmost_subnav_parent_retriever.php */
/* Location: ./WalkerCMS/helpers/topmost_subnav_parent_retriever.php */