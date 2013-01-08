<?php
interface IMailerAdapter
{
 function send_message($subject,
                       $sent_from,
                       $sent_to,
                       $content,
                       $sent_cc = null,
                       $sent_bcc = null,
                       $reply_to = null);
}

/* End of file imaileradapter.php */
/* Location: ./WalkerCMs/helpers/imaileradapter.php */