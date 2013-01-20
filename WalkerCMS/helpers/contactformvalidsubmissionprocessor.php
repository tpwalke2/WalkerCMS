<?php
class ContactFormValidSubmissionProcessor implements IDataProcessor
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
  $this->_logger->debug('[Contact Form] Valid contact form submission');
  $result['valid_input'] = true;
   
  $send_result = $this->_mailer->send_message(
    "[Contact Form] Message from '{$result['submitter_name']}'",
    array($this->_config->get('walkercms.admin_email') => $this->_config->get('walkercms.organization_name') . ' Website'),
    array($this->_config->get('walkercms.contact_email') => $this->_config->get('walkercms.contact_name')),
    $this->_view->generate_view('partials.contact_form_email_body', $result)->render(),
    array($this->_config->get('walkercms.admin_email') => $this->_config->get('walkercms.admin_name')),
    null,
    array($result['submitter_email'])
  );
  
  if ($send_result['status'])
  {
   $this->_logger->debug('[Contact Form] E-mail sent successfully');
   $context->set_contact_form_view('partials.contact_form_success');
  }
  else
  {
   $this->_logger->debug('[Contact Form] Contact form submission email failed');
   $context->set_contact_form_view('partials.contact_form_failure');
   $result['submission_errors'] = $send_result['submission_errors'];
   $result['admin_email'] = $this->_config->get('walkercms.admin_email');
  }
  
  return $result;
 }
}

/* End of file contactformvalidsubmissionprocessor.php */
/* Location: ./WalkerCMS/helpers/contactformvalidsubmissionprocessor.php */