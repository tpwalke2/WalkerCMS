<?php
use Laravel\Input;

class InputAdapter implements IInputAdapter
{
 public function all()
 {
  return Input::all();
 }
 
 public function get($key = null, $default = null)
 {
  return Input::get($key, $default);
 }
}

/* End of file inputadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/inputadapter.php */