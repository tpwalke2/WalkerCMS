<?php
class FormInvalidSubmissionProcessor implements IDataProcessor
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function process($result, $context)
 {
  $validation = $context->get_form_validation();
  $this->_logger->debug("[WalkerCMS] Form Input validation failed with following errors:\n" . implode("\n", $validation->get_all_errors()));
  $result['item_errors'] = array();
  foreach ($result['form']['indexed_items'] as $key=>$item)
  {
   $this->add_validation_error($validation, $item, $result);
  }
  
  return $result;
 }
 
 private function add_validation_error($validation, $item, &$data)
 {
  if ($validation->has_errors($item['input_name']))
  {
   $data['item_errors'][$item['fully_qualified_id']] = implode('&nbsp;', $validation->get_errors($item['input_name']));
  }
 }
}

/* End of file forminvalidsubmissionprocessor.php */
/* Location: ./WalkerCMS/helpers/forms/forminvalidsubmissionprocessor.php */