<?php
class ContactFormInvalidSubmissionProcessor implements IDataProcessor
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function process($result, $context)
 {
  $this->_logger->debug('[Contact Form] Input validation failed');
  $validation = $context->get_contact_validation();
  foreach (array('name', 'email', 'message') as $key)
  {
   $this->add_validation_error($validation, $key, $result);
  }
  
  return $result;
 }
 
 private function add_validation_error($validation, $errors_key, &$data)
 {
  if ($validation->has_errors($errors_key))
  {
   $data[$errors_key . '_validation_error'] = join('&nbsp;', $validation->get_errors($errors_key));
  }
 }
}

/* End of file contactforminvalidsubmissionprocessor.php */
/* Location: ./WalkerCMS/helpers/contactforminvalidsubmissionprocessor.php */