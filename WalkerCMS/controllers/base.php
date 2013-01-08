<?php

class Base_Controller extends Controller
{

 /**
  * Catch-all method for requests that can't be matched.
  *
  * @param  string    $method
  * @param  array     $parameters
  * @return Response
  */
 public function __call($method, $parameters)
 {
  Log::error("[WalkerCMS] Invalid method '$method'");
  return Response::error(404, compact('method', 'parameters'));
 }

}