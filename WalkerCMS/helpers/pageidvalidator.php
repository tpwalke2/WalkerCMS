<?php
class PageIDValidator implements IPageIDValidator
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function get_validated_page_id($pages, $page_id)
 {
  $this->_logger->debug("[WalkerCMS] Validating page ID '$page_id'");
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

/* End of file pageidvalidator.php */
/* Location: ./WalkerCMS/helpers/pageidvalidator.php */