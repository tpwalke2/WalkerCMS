<?php
use Laravel\Config;

class ConfigAdapter implements IConfigAdapter
{
 public function set($key, $value)
 {
  return Config::set($key, $value);
 }
 
 public function get($key, $default = null)
 {
  return Config::get($key, $default);
 }
}

/* End of file configadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/configadapter.php */