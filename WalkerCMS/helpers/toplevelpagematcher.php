<?php
class TopLevelPageMatcher implements IPageMatcher
{
 public function is_match($page, $parent)
 {
  if (!$page->get_show_in_nav()) { return false; }
  return ($page->get_parent() == '');
 }
}

/* End of file toplevelpagematcher.php */
/* Location: ./WalkerCMS/helpers/toplevelpagematcher.php */