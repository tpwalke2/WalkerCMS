<?php
interface IResponseAdapter
{
 function send_json($data);
 function error($code, $data = array());
}

/* End of file iresponseadapter.php */
/* Location: ./WalkerCMS/helpers/iresponseadapter.php */