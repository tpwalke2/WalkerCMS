<?php
class FormSpamSubmissionProcessor implements IDataProcessor
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function process($result, $context)
 {
  $context->set_form_result_view('forms.submit_failure');
  $this->_logger->debug('[WalkerCMS] Processing potential spam submission');
  $this->_logger->error('[WalkerCMS] Potential form spammer identified');
  $this->_logger->error("[WalkerCMS] IP Address: {$result['ip_address']}");
  $this->_logger->error("[WalkerCMS] User Agent: {$result['user_agent']}");
  $this->_logger->error("[WalkerCMS] Spam control: {$result['spam_control']}");
  $this->_logger->error("[WalkerCMS] Form ID: {$result['form']['id']}");
  $result['validation_errors'][] = 'Invalid entry.';
  
  return $result;
 }
}

/* End of file formspamsubmissionprocessor.php */
/* Location: ./WalkerCMS/helpers/forms/formspamsubmissionprocessor.php */