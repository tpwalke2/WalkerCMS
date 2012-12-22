<?php

class PageIDValidator
{
 public function get_validated_page_id($pages, $page_id)
 {
  $page_id = trim($page_id);
  if ($page_id == '') {
   return 'home';
  }
  if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $page_id)) {
   return '404';
  }
  if (isset($pages[$page_id])) {
   return $page_id;
  }
  return '404';
 }
}

/* End of file page_id_validator.php */
/* Location: ./WalkerCMS/helpers/page_id_validator.php */