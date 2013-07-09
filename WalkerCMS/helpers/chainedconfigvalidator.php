<?php
class ChainedConfigValidator implements IConfigValidator
{
 private $_validators = null;
 
 public function __construct()
 {
  $this->_validators = array();
  
  $args = func_get_args();
  foreach ($args as $arg)
  {
   $this->_validators[] = $arg;
  }
 }
 
 public function validate($config)
 {
  $valid = true;
  $errors = array();
  
  foreach ($this->_validators as $validator)
  {
   $inner_result = $validator->validate($config);
   if (!$inner_result['valid']) { $valid = false; }
   $errors = array_merge($errors, $inner_result['errors']);
  }
  
  return compact('valid', 'errors');
 }
}
/* End of file chainedconfigvalidator.php */
/* Location: ./WalkerCMS/helpers/chainedconfigvalidator.php */