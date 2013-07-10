<?php
class EmailInputRulesGenerator extends TextInputRulesGenerator
{
 public function get_rules($item)
 {
  $rules = parent::get_rules($item);
  
  if (strlen($rules) > 0) { $rules .= '|'; }
  
  return $rules . 'email';
 }
}

/* End of file emailinputrulesgenerator.php */
/* Location: ./WalkerCMS/helpers/forms/emailinputrulesgenerator.php */