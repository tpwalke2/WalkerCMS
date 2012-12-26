<?php
use Laravel\Config;
require_once(path('app') . 'helpers/interfaces/config_adapter.php');

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

/* End of file config_adapter.php */
/* Location: ./WalkerCMS/helpers/laravel/config_adapter.php */