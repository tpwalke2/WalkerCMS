<?php
use Laravel\Validator;

class ValidatorAdapter implements IValidatorAdapter
{
 public function create_validator($input, $rules, $messages = array())
 {
  return new ValidationWrapper(Validator::make($input, $rules, $messages = array()));
 }
}

/* End of file validatoradapter.php */
/* Location: ./WalkerCMS/helpers/laravel/validatoradapter.php */