<?php
class ContactFormResultDataFactory implements IDataFactory
{
 private $_input = null;
 private $_request = null;
 private $_config = null;
 private $_logger = null;
 
 function __construct($input, $request, $config, $logger)
 {
  $this->_input = $input;
  $this->_request = $request;
  $this->_config = $config;
  $this->_logger = $logger;
 }
 
 public function create()
 {
  $this->_logger->debug('[Contact Form] Generating default result data');
  return array(
    'submitting_page_id' => $this->_input->get('page_id', $this->_config->get('walkercms.contact_page')),
    'submitter_name'     => $this->_input->get('name', ''),
    'submitter_email'    => $this->_input->get('email', ''),
    'message'            => $this->_input->get('message', ''),
    'spam_control'       => $this->_input->get('required_control', ''),
    'ip_address'         => $this->_request->ip_address(),
    'user_agent'         => $this->_request->user_agent(),
    'valid_input'        => false,
    'organization_name'  => $this->_config->get('walkercms.organization_name'),
  );
 }
}

/* End of file contactformresultdatafactory.php */
/* Location: ./WalkerCMS/helpers/contactformresultdatafactory.php */