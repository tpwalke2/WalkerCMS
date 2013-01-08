<?php
use Laravel\Validator;

class ValidatorAdapter implements IValidatorAdapter
{
 public function create_validator($input, $rules)
 {
  return Validator::make($input, $rules);
 }
}

/* End of file validatoradapter.php */
/* Location: ./WalkerCMS/helpers/laravel/validatoradapter.php */