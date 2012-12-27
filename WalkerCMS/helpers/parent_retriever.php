<?php
require_once(path('app') . 'helpers/interfaces/parent_retriever.php');

class ParentRetriever implements IParentRetriever
{
 public function get_parent($pages, $page)
 {
  if ($page->get_parent() == '') { return null; }
  if (!isset($pages[$page->get_parent()])) { return null; }
  return $pages[$page->get_parent()];
 }
}

/* End of file parent_retriever.php */
/* Location: ./WalkerCMS/helpers/parent_retriever.php */