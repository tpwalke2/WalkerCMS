<?php
class SessionAdapter implements ISessionAdapter
{
 public function get($key = null)
 {
  return Session::get($key);
 }
 
 public function forget($key)
 {
  return Session::forget($key);
 }
 
 public function has($key)
 {
  return Session::has($key);
 }
 
 public function put($key, $value)
 {
  return Session::put($key, $value);
 }
}
/* End of file sessionadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/sessionadapter.php */