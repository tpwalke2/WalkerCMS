<?php
class ContactFormValidatorRetriever implements IValidatorRetriever
{
 private $_validator = null;
 private $_input = null;
 private $_logger = null;
 
 function __construct($validator, $input, $logger)
 {
  $this->_validator = $validator;
  $this->_input = $input;
  $this->_logger = $logger;
 }
 
 public function get_contact_form_validator()
 {
  $this->_logger->debug('[Contact Form] Retrieving contact form validator');
  $rules = array(
    'name'    => 'required|max:50',
    'email'   => 'required|max:255|email',
    'message' => 'required|max:2000'
  );
  return $this->_validator->create_validator($this->_input->all(), $rules);
 }
}
/* End of file contactformvalidatorretriever.php */
/* Location: ./WalkerCMS/helpers/contactformvalidatorretriever.php */