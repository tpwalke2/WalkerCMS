<?php
require_once(path('app') . 'helpers/interfaces/page_retriever.php');

class ParentRetriever implements IPageRetriever
{
 public function get_page($pages, $working_page)
 {
  if ($working_page->get_parent() == '') { return null; }
  if (!isset($pages[$working_page->get_parent()])) { return null; }
  return $pages[$working_page->get_parent()];
 }
}

/* End of file parent_retriever.php */
/* Location: ./WalkerCMS/helpers/parent_retriever.php */