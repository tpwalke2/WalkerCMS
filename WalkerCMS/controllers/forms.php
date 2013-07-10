<?php
use Laravel\Response;

class Forms_Controller extends Base_Controller
{
 public $restful = true;
 
 private $_result_factory = null;
 private $_context_factory = null;
 private $_validation_retriever = null;
 private $_invalid_submission_processor = null;
 private $_spam_submission_processor = null;
 private $_valid_submission_processor = null;
 private $_response_retriever = null;
 
 public function __construct(
   $result_factory,
   $context_factory,
   $validation_retriever,
   $invalid_submission_processor,
   $spam_submission_processor,
   $valid_submission_processor,
   $response_retriever,
   $response,
   $logger)
 {
  parent::__construct($response, $logger);
  $this->_result_factory = $result_factory;
  $this->_context_factory = $context_factory;
  $this->_validation_retriever = $validation_retriever;
  $this->_invalid_submission_processor = $invalid_submission_processor;
  $this->_spam_submission_processor = $spam_submission_processor;
  $this->_valid_submission_processor = $valid_submission_processor;
  $this->_response_retriever = $response_retriever;
 }
 
 public function post_form_submission()
 {
  $this->_logger->debug('[WalkerCMS] Processing form submission.');
   
  $result = $this->_result_factory->create();
  $context = $this->_context_factory->create($result['submitting_page_id']);
  $context->set_form_validation($this->_validation_retriever->get_form_validator());
  if ($context->get_form_validation()->fails()) { $result = $this->_invalid_submission_processor->process($result, $context); }
  elseif ($result['spam_control'] != '') { $result = $this->_spam_submission_processor->process($result, $context); }
  else { $result = $this->_valid_submission_processor->process($result, $context); }
  
  $context->clear_form_validation();
  $context->set_form_data($result);
  
  return $this->_response_retriever->get_response($result, $context);
 }
}

/* End of file forms.php */
/* Location: ./WalkerCMS/controllers/forms.php */