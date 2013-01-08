<?php
class ContactFormDataGenerator implements IDataGenerator
{
 private $_config = null;
 private $_view = null;
 private $_logger = null;
 
 public function __construct($config, $view, $logger)
 {
  $this->_config = $config;
  $this->_view = $view;
  $this->_logger = $logger;
 }
 
 public function generate_data($working_page, $context)
 {
  $this->_logger->debug('[WalkerCMS] Generating contact form data.');
  if ($this->_config->get('walkercms.contact_page') != $working_page->get_id()) { return null; }
  $contact_form_data = $context->get_contact_form_data(
    array(
      'submitter_name'     => '',
      'submitter_email'    => '',
      'message'            => '',
      'submitting_page_id' => $working_page->get_id()
      )
    );
  $contact_form_view = $context->get_contact_form_view('partials.contact_form');
  
  return array('contact_form' => $this->_view->generate_view($contact_form_view, $contact_form_data));
 }
}

/* End of file contactformdatagenerator.php */
/* Location: ./WalkerCMS/helpers/contactformdatagenerator.php */