<?php
class FormValidatorRetriever implements IValidatorRetriever
{
 private $_rule_generators = null;
 private $_config = null;
 private $_validator = null;
 private $_input = null;
 private $_logger = null;
 
 function __construct($rule_generators, $config, $validator, $input, $logger)
 {
  $this->_rule_generators = $rule_generators;
  $this->_config = $config;
  $this->_validator = $validator;
  $this->_input = $input;
  $this->_logger = $logger;
 }
 
 public function get_form_validator()
 {
  $this->_logger->debug('[WalkerCMS] Retrieving form validation rules');
  if (count($this->_rule_generators) == 0) { return null; }
  
  $input = $this->_input->all();
  if (!isset($input['form_id'])) { return null; }
  $form_id = $input['form_id'];
  if (trim($form_id) == '')  { return null; }
  
  $forms = $this->_config->get('walkercms.forms');
  if (!isset($forms[$form_id])) { return null; }
  $form = $forms[$form_id];
  
  $rules = array();
  foreach ($form['indexed_items'] as $id=>$item)
  {
   $sanitized_item_id = 'data_' . str_replace('.', '_', $id);
   if (!isset($this->_rule_generators[$item['type']])) { continue; }
   $candidate_rule = trim($this->_rule_generators[$item['type']]->get_rules($item));
   if (strlen($candidate_rule) == 0) { continue; }
   $rules[$sanitized_item_id] = $candidate_rule;
  }
  
  if (count($rules) == 0) { return null; }
  
  $messages = array(
    'required' => '*',
    'in'       => '*',
  );
  
  return $this->_validator->create_validator($input, $rules, $messages);
 }
}

/* End of file formvalidatorretriever.php */
/* Location: ./WalkerCMS/helpers/forms/formvalidatorretriever.php */