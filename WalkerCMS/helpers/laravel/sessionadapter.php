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
}
/* End of file sessionadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/sessionadapter.php */