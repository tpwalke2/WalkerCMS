<?php
use Laravel\Config;
use Laravel\Log;

class LoggerAdapter implements ILoggerAdapter
{
 public function debug($msg)
 {
  if (strtolower(Config::get('walkercms.log_level', 'error')) == 'debug')
  {
   Log::debug($msg);
  }
 }
 
 public function error($msg)
 {
  Log::error($msg);
 }
}

/* End of file loggeradapter.php */