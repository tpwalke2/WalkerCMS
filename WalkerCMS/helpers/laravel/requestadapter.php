<?php
use Laravel\Request;

class RequestAdapter implements IRequestAdapter
{
 public function ip_address()
 {
  return Request::ip();
 }
 
 public function user_agent()
 {
  if (Request::$foundation->server->has('HTTP_USER_AGENT'))
  {
   return Request::$foundation->server->get('HTTP_USER_AGENT');
  }
 }
 
 public function is_ajax()
 {
  return Request::ajax();
 }
}

/* End of file requestadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/requestadapter.php */