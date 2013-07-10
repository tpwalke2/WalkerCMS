<?php
class AppContext
{
 private $_cache;
 
 public function get_current_page()
 {
  $store = $this->get_page_store();
  $current_page_id = $this->get_current_page_id();
  
  if (isset($store) && isset($current_page_id))
  {
   return $store->get_page($current_page_id);
  }
  
  return null;
 }
 
 public function get_pages()
 {
  $store = $this->get_page_store();
  if (isset($store)) { return $store->get_all_pages(); }
  return null;
 }
 
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
  
  if (!preg_match('/^(set|get|clear)_(.+)$/', $method, $matches))
  {
   throw new BadMethodCallException("Invalid method '$method' invoked on app context.");
  }
  
  $accessor = $matches[1];
  $key = $matches[2];
  
  switch ($accessor)
  {
   case 'get':
    $default = null;
    if (count($parameters) > 0) { $default = $parameters[0]; }
    if (!isset($this->_cache[$key])) { return $default; }
    return $this->_cache[$key];
    break;
   case 'set':
    $this->_cache[$key] = $parameters[0];
    break;
   case 'clear':
     $this->_cache[$key] = null;
     break;
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