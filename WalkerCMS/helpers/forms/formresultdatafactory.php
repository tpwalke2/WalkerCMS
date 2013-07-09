<?php
class FormResultDataFactory implements IDataFactory
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
  $this->_logger->debug('[WalkerCMS] Generating default form result data');
  $result = array(
    'submitting_page_id' => $this->_input->get('page_id'),
    'form_id'            => $this->_input->get('form_id'),
    'spam_control'       => $this->_input->get('required_control', ''),
    'ip_address'         => $this->_request->ip_address(),
    'user_agent'         => $this->_request->user_agent(),
    'valid_input'        => false,
    'organization_name'  => $this->_config->get('walkercms.organization_name'),
  );
  
  $forms = $this->_config->get('walkercms.forms');
  $result['form'] = $forms[$result['form_id']];
  
  foreach ($this->_input->all() as $key=>$value)
  {
   if (!preg_match('/^data_/', $key)) { continue; }
   $result['item_values'][$key] = $value;
  }
  
  return $result;
 }
}

/* End of file formresultdatafactory.php */
/* Location: ./WalkerCMS/helpers/forms/formresultdatafactory.php */