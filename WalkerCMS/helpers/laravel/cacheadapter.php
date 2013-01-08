<?php
use Laravel\Cache;

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

/* End of file cacheadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/cacheadapter.php */