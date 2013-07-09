<?php
class BaseRulesGenerator implements IRulesGenerator
{
 public function get_rules($item)
 {
  if (isset($item['required']) && $item['required']) { return 'required'; }
  return '';
 }
}

/* End of file baserulesgenerator.php */
/* Location: ./WalkerCMS/helpers/forms/baserulesgenerator.php */