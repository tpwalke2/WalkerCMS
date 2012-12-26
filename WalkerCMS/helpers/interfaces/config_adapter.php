<?php
interface IConfigAdapter
{
 function set($key, $value);
 function get($key, $default = null);
}

/* End of file config_adapter.php */
/* Location: ./WalkerCMS/helpers/interfaces/config_adapter.php */