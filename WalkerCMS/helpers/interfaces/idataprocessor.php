<?php
interface IDataProcessor
{
 /**
  * This function performs some operation on the result and then returns the
  * (potentially) modified result.
  * 
  * @param array $result
  * @param AppContext $context
  */
 function process($result, $context);
}

/* End of file idataprocessor.php */
/* Location: ./WalkerCMS/helpers/idataprocessor.php */