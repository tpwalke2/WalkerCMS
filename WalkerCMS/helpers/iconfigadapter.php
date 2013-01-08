<?php
interface IConfigAdapter
{
 function set($key, $value);
 function get($key, $default = null);
}

/* End of file iconfigadapter.php */
/* Location: ./WalkerCMS/helpers/iconfigadapter.php */