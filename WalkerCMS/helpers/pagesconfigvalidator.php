<?php
class PagesConfigValidator implements IConfigValidator
{
 private $_reserved_page_ids = array('site', 'sitemap');
 
 public function validate($config)
 {
  $valid = true;
  $errors = array();
  
  if (isset($config['pages']))
  {
   foreach ($config['pages'] as $key=>$page)
   {
    if (in_array($key, $this->_reserved_page_ids))
    {
     $valid = false;
     $errors[] = "The page ID '$key' is reserved.";
    }
   }
  }
  
  return compact('valid', 'errors');
 }
}

/* End of file pagesconfigvalidator.php */
/* Location: ./WalkerCMS/helpers/pagesconfigvalidator.php */