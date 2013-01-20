<?php
class ContactFormSpamSubmissionProcessor implements IDataProcessor
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function process($result, $context)
 {
  $this->_logger->debug('[Contact Form] Processing potential spam submission');
  $this->_logger->error('[Contact Form] Potential form spammer identified');
  $this->_logger->error("[Contact Form] IP Address: {$result['ip_address']}");
  $this->_logger->error("[Contact Form] User Agent: {$result['user_agent']}");
  $this->_logger->error("[Contact Form] Name: {$result['submitter_name']}");
  $this->_logger->error("[Contact Form] Email: {$result['submitter_email']}");
  $result['validation_errors'][] = 'Invalid entry.';
  
  return $result;
 }
}

/* End of file contactformspamsubmissionprocessor.php */
/* Location: ./WalkerCMS/helpers/contactformspamsubmissionprocessor.php */