<?php
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

/* End of file loggeradapter.php */
/* Location: ./WalkerCMS/Helpers/Laravel/loggeradapter.php */