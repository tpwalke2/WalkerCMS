<?php
if (!function_exists('is_child_of_parent'))
{
 function is_child_of_parent($page, $parent) {
  return ($page['parent'] == $parent['id']);
 }
}

if (!function_exists('is_child_page'))
{
 function is_child_page($page) {
  return ($page['parent'] != '');
 }
}

if (!function_exists('get_parent_page_id'))
{
 function get_parent_page_id($page) {
  if (isset($page['parent'])) {
   return $page['parent'];
  }
  return '';
 }
}

if (!function_exists('identity'))
{
 function identity($pages, $page, $currentPage) {
  return $page;
 }
}

if (!function_exists('get_first_child'))
{
 function get_first_child($pages, $page) {
  $children = filter_pages($pages, 'is_child_of_parent', $page);
  if (count($children) > 0) {
   return $children[0];
  }
  return $page;
 }
}

if (!function_exists('filter_pages'))
{
 function filter_pages($pages, $filterCallback, $currentPage) {
  $result = array();

  foreach ($pages as $pageID=>$page) {
   if (call_user_func($filterCallback, $page, get_topmost_with_sub_nav($pages, $currentPage))) {
    $result[] = $page;
   }
  }

  return $result;
 }
}

/* End of file pages_helper.php */
/* Location: ./applications/helpers/pages_helper.php */