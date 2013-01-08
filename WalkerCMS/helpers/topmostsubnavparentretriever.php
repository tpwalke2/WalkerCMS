<?php
class TopMostSubNavParentRetriever implements IPageRetriever
{
 public function get_page($pages, $page)
 {
  if ($page->get_sub_nav_on_page()) { return $page; }
  if ($page->get_parent() == '') { return $page; }
  return $this->get_page($pages, $pages[$page->get_parent()]);
 }
}

/* End of file topmostsubnavparentretriever.php */
/* Location: ./WalkerCMS/helpers/topmostsubnavparentretriever.php */