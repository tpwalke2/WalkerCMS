<?php
require_once(path('app') . 'helpers/interfaces/page_matcher.php');

class PageChildMatcher implements IPageMatcher
{
 public function is_match($page, $parent)
 {
  return ($page->get_parent() == $parent->get_id());
 }
}

/* End of file page_child_matcher.php */
/* Location: ./WalkerCMS/helpers/page_child_matcher.php */