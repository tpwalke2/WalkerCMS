<?php
class ContactFormResponseRetriever implements IResponseRetriever
{
 private $_request = null;
 private $_contact_form_generator = null;
 private $_response = null;
 private $_redirect = null;
 private $_logger = null;
 
 function __construct(
   $request,
   $contact_form_generator,
   $response,
   $redirect,
   $logger
   )
 {
  $this->_request = $request;
  $this->_contact_form_generator = $contact_form_generator;
  $this->_response = $response;
  $this->_redirect = $redirect;
  $this->_logger = $logger;
 }
 
 public function get_response($result, $context)
 {
  if ($this->_request->is_ajax())
  {
   $this->_logger->debug('[Contact Form] Generating JSON response.');
   $content = $this->_contact_form_generator->generate_data($context->get_current_page(), $context);
   $result['content'] = $content['contact_form']->render();
   return $this->_response->send_json($result);
  }
  
  $this->_logger->debug('[Contact Form] Generating redirect response.');
  return $this->_redirect->to($result['submitting_page_id'], array('context' => $context));
 }
}

/* End of file contactformresponseretriever.php */
/* Location: ./WalkerCMS/helpers/contactformresponseretriever.php */