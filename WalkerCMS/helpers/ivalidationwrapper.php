<?php
interface IValidationWrapper
{
 function fails();
 function has_errors($key);
 function get_errors($key);
}

/* End of file ivalidationwrapper.php */
/* Location: ./WalkerCMS/helpers/ivalidationwrapper.php */