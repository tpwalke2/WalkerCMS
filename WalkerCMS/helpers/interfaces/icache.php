<?php
interface ICache
{
 function has($key);
 function get($key, $default = null);
 function put($key, $value, $minutes);
 function remember($key, $default, $minutes);
 function forget($key);
}

/* End of file icache.php */
/* Location: ./WalkerCMS/helpers/icache.php */