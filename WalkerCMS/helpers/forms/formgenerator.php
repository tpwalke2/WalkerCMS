<?php
class FormGenerator
{
 private $_data_generator = null;
 private $_view = null;
 private $_logger = null;
 
 function __construct($data_generator, $view_adapter, $logger_adapter)
 {
  $this->_data_generator = $data_generator;
  $this->_view = $view_adapter;
  $this->_logger = $logger_adapter;
 }
 
 public function generate($form_id, $context)
 {
  $this->_logger->debug("[WalkerCMS] Generating form '$form_id'");
  $view = $context->get_form_result_view('forms.main');
  $context->clear_form_result_view();
  
  return $this->_view->generate_view($view, $this->_data_generator->generate($form_id, $context));
 }
}

/* End of file formgenerator.php */
/* Location: ./WalkerCMs/helpers/forms/formgenerator.php */