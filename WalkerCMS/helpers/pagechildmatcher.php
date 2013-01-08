<?php
class PageChildMatcher implements IPageMatcher
{
 public function is_match($page, $parent)
 {
  return ($page->get_parent() == $parent->get_id());
 }
}

/* End of file pagechildmatcher.php */
/* Location: ./WalkerCMS/helpers/pagechildmatcher.php */