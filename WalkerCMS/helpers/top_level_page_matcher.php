<?php
require_once(path('app') . 'helpers/interfaces/page_matcher.php');

class TopLevelPageMatcher implements IPageMatcher
{
 public function is_match($page, $parent)
 {
  if (!$page->get_show_in_nav()) { return false; }
  return ($page->get_parent() == '');
 }
}

/* End of file top_level_page_matcher.php */
/* Location: ./WalkerCMS/helpers/top_level_page_matcher.php */