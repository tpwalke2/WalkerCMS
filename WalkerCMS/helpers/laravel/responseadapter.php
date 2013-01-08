<?php
use Laravel\Response;

class ResponseAdapter implements IResponseAdapter
{
 public function send_json($data)
 {
  return Response::json($data);
 }
}

/* End of file responseadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/responseadapter.php */