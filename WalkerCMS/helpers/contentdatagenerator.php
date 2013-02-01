<?php
class ContentDataGenerator implements IDataGenerator
{
 private $_inclusion_generator = null;
 private $_contact_form_generator = null;
 private $_config = null;
 private $_logger = null;
 
 function __construct($inner_generator, $contact_form_generator, $config, $logger)
 {
  $this->_inclusion_generator = $inner_generator;
  $this->_contact_form_generator = $contact_form_generator;
  $this->_config = $config;
  $this->_logger = $logger;
 }
 
 public function generate_data($working_page, $context)
 {
  $content_source_page = $context->get_content_source_page();
  $this->_logger->debug("[WalkerCMS] Generating content data for page '{$content_source_page->get_id()}'");
  
  $result = $this->_inclusion_generator->generate_data($content_source_page, $context);
  $result['site_version'] = $this->_config->get('walkercms.version');
  
  $contact_form_data = $this->_contact_form_generator->generate_data($content_source_page, $context);
  
  if ($contact_form_data == null) { return $result; }
  return array_merge($result, $contact_form_data);
 }
}

/* End of file contentdatagenerator.php */
/* Location: ./WalkerCMS/helpers/contentdatagenerator.php */