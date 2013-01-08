<?php
use Laravel\Bundle;
use Laravel\IoC;

class SwiftMailerAdapter implements IMailerAdapter
{
 public function send_message($subject,
                              $sent_from,
                              $sent_to,
                              $content,
                              $sent_cc = null,
                              $sent_bcc = null,
                              $reply_to = null)
 {
  $expected_recipient_count = count($sent_to);
  
  Bundle::start('swiftmailer');
  $mailer = IoC::resolve('mailer');
  $message = Swift_Message::newInstance($subject)
                            ->setFrom($sent_from)
                            ->setTo($sent_to)
                            ->setBody($content,'text/plain');
  
  if (!$this->is_undefined_or_null($sent_cc))
  {
   $message->setCc($sent_cc);
   $expected_recipient_count += count($sent_cc);
  }
  
  if (!$this->is_undefined_or_null($sent_bcc))
  {
   $message->setBcc($sent_bcc);
   $expected_recipient_count += count($sent_bcc);
  }
  
  if (!$this->is_undefined_or_null($reply_to))
  {
   $message->setReplyTo($reply_to);
  }
  
  $status = false;
  $failed_recipients = null;
  if ($mailer->send($message, $failed_recipients) === $expected_recipient_count) { $status = true; }
  $submission_errors = '';
  if (count($failed_recipients) > 0)
  {
   $submission_errors = 'Your message was unable to be sent to the following recipients: ' . join(',', $failed_recipients);
  }
  return compact('status', 'submission_errors');
 }
 
 private function is_undefined_or_null($value)
 {
  if (!isset($value)) { return true; }
  if ($value == null) { return true; }
  return false;
 }
}

/* End of file swiftmaileradapter.php */
/* Location: ./WalkerCMS/helpers/laravel/swiftmaileradapter.php */