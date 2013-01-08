<?php
class ParentRetriever implements IPageRetriever
{
 public function get_page($pages, $page)
 {
  if ($page->get_parent() == '') { return null; }
  if (!isset($pages[$page->get_parent()])) { return null; }
  return $pages[$page->get_parent()];
 }
}

/* End of file parentretriever.php */
/* Location: ./WalkerCMS/helpers/parentretriever.php */