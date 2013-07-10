<?php
class TextInputRulesGenerator extends BaseRulesGenerator
{
 public function get_rules($item)
 {
  $rules = parent::get_rules($item);
  
  if (!isset($item['max_length'])) { return $rules; }
  
  if (strlen($rules) > 0) { $rules .= '|'; }
  
  return $rules . 'max:' . $item['max_length'];
 }
}

/* End of file textinputrulesgenerator.php */
/* Location: ./WalkerCMS/helpers/forms/textinputrulesgenerator.php */