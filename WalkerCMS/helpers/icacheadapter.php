<?php
interface ICacheAdapter
{
 function has($key);
 function get($key, $default = null);
 function put($key, $value, $minutes);
 function remember($key, $default, $minutes);
 function forget($key);
}

/* End of file icacheadapter.php */
/* Location: ./WalkerCMS/helpers/icacheadapter.php */