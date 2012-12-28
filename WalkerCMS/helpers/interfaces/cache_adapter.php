<?php
interface ICacheAdapter
{
 function has($key);
 function get($key, $default = null);
 function put($key, $value, $minutes);
 function remember($key, $default, $minutes);
 function forget($key);
}

/* End of file cache_adapter.php */
/* Location: ./WalkerCMS/helpers/interfaces/cache_adapter.php */