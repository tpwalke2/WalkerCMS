<?php

class Base_Controller extends Controller
{
 protected $_response = null;
 protected $_logger = null;
 
 function __construct($response, $logger)
 {
  $this->_response = $response;
  $this->_logger = $logger;
 }
 
 /**
  * Catch-all method for requests that can't be matched.
  *
  * @param  string    $method
  * @param  array     $parameters
  * @return Response
  */
 public function __call($method, $parameters)
 {
  $this->_logger->error("[WalkerCMS] Invalid method '$method'");
  return $this->_response->error(404, compact('method', 'parameters'));
 }

}