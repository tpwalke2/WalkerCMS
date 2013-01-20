<?php
use Laravel\Response;

class ResponseAdapter implements IResponseAdapter
{
 public function send_json($data)
 {
  return Response::json($data);
 }
 
 public function error($code, $data = array())
 {
  return Response::error($code, $data);
 }
}

/* End of file responseadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/responseadapter.php */