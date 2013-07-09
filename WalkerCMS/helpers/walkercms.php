<?php
use Laravel\IoC;

class WalkerCMS
{
 private static $_generator;
 private static $_session;
 
 public static function generate_form($form_id)
 {
  if (static::$_generator == null) { static::$_generator = IoC::resolve('form_generator'); }
  if (static::$_session == null) { static::$_session = IoC::resolve('session_adapter'); }
  $context = static::$_session->get('context');
  
  return static::$_generator->generate($form_id, $context);
 }
}

/* End of file walkercms.php */
/* Location: ./WalkerCMS/helpers/walkercms.php */