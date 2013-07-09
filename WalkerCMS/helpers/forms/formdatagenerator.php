<?php
class FormDataGenerator implements IFormDataGenerator
{
 private $_config = null;
 private $_logger = null;
 
 function __construct($config_adapter, $logger_adapter)
 {
  $this->_config = $config_adapter;
  $this->_logger = $logger_adapter;
 }
 
 public function generate($form_id, $context)
 {
  $this->_logger->debug("[WalkerCMS] Generating data for form '$form_id'");
  $forms = $this->_config->get('walkercms.forms');
  $form = $forms[$form_id];
    
  $data = array(
    'form'             => $form,
    'full_month_names' => array(),
    'dates'            => array(),
    'years'            => array(),
    'page_id'          => $context->get_current_page_id(),
    );
  
  for ($i = 1; $i <= 12; $i++)
  {
   $data['full_month_names'][] = date("F", mktime(0, 0, 0, $i+1, 0, 0));
  }
  
  for ($i = 1; $i <= 31; $i++)
  {
   $data['dates'][$i] = $i;
  }
  
  $current_year = date('Y');
  $total_years = $current_year - 1900;
  for ($i = 0; $i <= $total_years; $i++)
  {
   $data['years'][$current_year - $i] = $current_year - $i;
  }
  
  $existing_form_data = $context->get_form_data();
  $data['item_values'] = array();
  $data['item_errors'] = array();
  
  if ($existing_form_data != null)
  {
   $data = array_merge($data, $existing_form_data);
   $context->clear_form_data();
  }
  
  foreach ($form['indexed_items'] as $key=>$item)
  {
   if (!isset($data['item_values'][$item['input_name']]))
   {
    $data['item_values'][$item['input_name']] = '';
   }
  }
  
  return $data;
 }
}

/* End of file formdatagenerator.php */
/* Location: ./WalkerCMS/helpers/forms/formdatagenerator.php */