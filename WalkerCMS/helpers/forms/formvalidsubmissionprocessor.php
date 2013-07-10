<?php
class FormValidSubmissionProcessor implements IDataProcessor
{
 private $_mailer = null;
 private $_config = null;
 private $_view = null;
 private $_logger = null;
 
 function __construct($mailer, $config, $view, $logger)
 {
  $this->_mailer = $mailer;
  $this->_config = $config;
  $this->_view = $view;
  $this->_logger = $logger;
 }
 
 public function process($result, $context)
 {
  $this->_logger->debug('[WalkerCMS] Valid form submission');
  $result['valid_input'] = true;
  
  $data = array(
    'form_id'          => $result['form']['id'],
    'form_description' => $result['form']['description'],
    'spam_control'     => $result['spam_control'],
    'ip_address'       => $result['ip_address'],
    'user_agent'       => $result['user_agent'],
    'input_items'      => array(),
  );
  
  foreach ($result['form']['indexed_items'] as $key=>$item)
  {
   $description = $item['description'];
   $value = '';
   if (isset($result['item_values'][$item['input_name']])) { $value = trim($result['item_values'][$item['input_name']]); }
   if ($value == '') { $value = '[NO ENTRY]'; }
   
   $data['input_items'][] = compact('description', 'value');
  }
   
  $send_result = $this->_mailer->send_message(
    "[{$result['form']['description']}] Data submission",
    array($this->_config->get('walkercms.admin_email') => $this->_config->get('walkercms.organization_name') . ' Website'),
    array($result['form']['recipient_email'] => $result['form']['recipient_name']),
    $this->_view->generate_view('forms.email_body', $data)->render());
  
  if ($send_result['status'])
  {
   $this->_logger->debug('[WalkerCMS] E-mail sent successfully');
   $context->set_form_result_view('forms.submit_success');
  }
  else
  {
   $this->_logger->debug('[WalkerCMS] Contact form submission email failed');
   $context->set_form_result_view('forms.submit_failure');
   $result['form_description'] = $data['form_description'];
   $result['submission_errors'] = $send_result['submission_errors'];
   $result['admin_email'] = $this->_config->get('walkercms.admin_email');
  }
  
  return $result;
 }
}

/* End of file formvalidsubmissionprocessor.php */
/* Location: ./WalkerCMS/helpers/forms/formvalidsubmissionprocessor.php */