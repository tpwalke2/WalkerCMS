<?php
interface ISessionAdapter
{
 function get($key = null);
 function forget($key);
}

/* End of file isessionadapter.php */
/* Location: ./WalkerCMS/helpers/isessionadapter.php */