<?php
use Laravel\Redirect;

class RedirectAdapter implements IRedirectAdapter
{
 public function to($action_name, $additional_data = null)
 {
  if ($additional_data == null) { $additional_data = array(); }
  if (!is_array($additional_data)) { throw new InvalidArgumentException('Additional data parameter is not an array.'); }
  
  $result = Redirect::to($action_name);
  
  foreach ($additional_data as $key=>$value)
  {
   $result = $result->with($key, $value);
  }
  
  return $result;
 }
}

/* End of file redirectadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/redirectadapter.php */