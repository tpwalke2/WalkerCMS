<?php
use Laravel\Cache;

require_once(path('app') . 'helpers/interfaces/cache_adapter.php');

class CacheAdapter implements ICacheAdapter
{
 public function has($key)
 {
  return Cache::has($key);
 }
 
 public function get($key, $default = null)
 {
  return Cache::get($key, $default);
 }
 
 public function put($key, $value, $minutes)
 {
  return Cache::put($key, $value, $minutes);
 }
 
 public function remember($key, $default, $minutes)
 {
  return Cache::remember($key, $default, $minutes);
 }
 
 public function forget($key)
 {
  return Cache::forget($key);
 }
}

/* End of file cache_adapter.php */
/* Location: ./WalkerCMS/helpers/laravel/cache_adapter.php */