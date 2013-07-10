<?php
interface ISessionAdapter
{
 function get($key = null);
 function forget($key);
 function has($key);
 function put($key, $value);
}

/* End of file isessionadapter.php */
/* Location: ./WalkerCMS/helpers/isessionadapter.php */