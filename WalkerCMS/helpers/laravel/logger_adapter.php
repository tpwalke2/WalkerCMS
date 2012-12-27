<?php
require_once(path('app') . 'helpers/interfaces/logger_adapter.php');
use Laravel\Log;

class LoggerAdapter implements ILoggerAdapter
{
 public function debug($msg)
 {
  Log::debug($msg);
 }
 
 public function error($msg)
 {
  Log::error($msg);
 }
}

/* End of file logger_adapter.php */
/* Location: /logger_adapter.php */