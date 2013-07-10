<?php
class MultipleChoiceRulesGenerator extends BaseRulesGenerator
{
 public function get_rules($item)
 {
  $rules = parent::get_rules($item);
  
  if (!isset($item['choices']) || (count($item['choices']) == 0)) { return $rules; }
  
  if (strlen($rules) > 0) { $rules .= '|'; }
  
  $rules .= 'in:';
  
  $sep = '';
  
  if (!isset($item['required']) || !$item['required'])
  {
   $rules .= '0,';
   $sep = ',';
  }
  
  $rules .= implode(',', array_keys($item['choices']));
  
  return $rules;
 }
}

/* End of file multiplechoicerulesgenerator.php */
/* Location: ./WalkerCMS/helpers/forms/multiplechoicerulesgenerator.php */