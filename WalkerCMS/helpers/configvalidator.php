<?php
class ConfigValidator implements IConfigValidator
{
 public function validate($config)
 {
  $valid = true;
  $errors = array();
  
  if (isset($config['pages']))
  {
   foreach ($config['pages'] as $key=>$page)
   {
    if ($key == 'site')
    {
     $valid = false;
     $errors[] = 'The page ID \'site\' is reserved.';
    }
   }
  }
  
  return compact('valid', 'errors');
 }
}

/* End of file configvalidator.php */
/* Location: ./WalkerCMS/helpers/configvalidator.php */