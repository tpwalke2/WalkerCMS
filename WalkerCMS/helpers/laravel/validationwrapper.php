<?php
class ValidationWrapper implements IValidationWrapper
{
 private $_inner_validation = null;
 
 function __construct($inner_validation)
 {
  $this->_inner_validation = $inner_validation;
 }
 
 public function fails()
 {
  return $this->_inner_validation->fails();
 }
 
 public function has_errors($key)
 {
  return $this->_inner_validation->errors->has($key);
 }
 
 public function get_errors($key)
 {
  return $this->_inner_validation->errors->get($key);
 }
 
 public function get_all_errors()
 {
  return $this->_inner_validation->errors->all();
 }
}

/* End of file validationwrapper.php */
/* Location: ./WalkerCMS/helpers/laravel/validationwrapper.php */