<?php
class AppContext
{
 private $_cache;
 
 /**
  * Interaction with the context occurs solely here at the catch-all method.
  * Methods with 'get_' and 'set_' prefixes are interpreted as getters and
  * setters respectively for items in the cache. The portion of the method
  * name after the prefix indicates the cache key.
  *  
  * @param string $method
  * @param mixed $parameters
  */
 public function __call($method, $parameters)
 {
  $matches = array();
  
  if (!preg_match('/^(set|get)_(.+)$/', $method, $matches))
  {
   throw new BadMethodCallException("Invalid method '$method' invoked on app context.");
  }
  
  $accessor = $matches[1];
  $key = $matches[2];
  
  if ($accessor == 'get')
  {
   $default = null;
   if (count($parameters) > 0) { $default = $parameters[0]; }
   if (!isset($this->_cache[$key])) { return $default; }
   return $this->_cache[$key];
  }
  elseif ($accessor == 'set')
  {
   $this->_cache[$key] = $parameters[0];
  }
 }
 
 public function __toString()
 {
  ob_start();
  print_r($this->_cache);
  $result = ob_get_contents();
  ob_end_clean();
  return $result;
 }
}

/* End of file appcontext.php */
/* Location: ./WalkerCMS/models/appcontext.php */