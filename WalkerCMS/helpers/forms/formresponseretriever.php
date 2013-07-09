<?php
class FormResponseRetriever implements IResponseRetriever
{
 private $_request = null;
 private $_form_generator = null;
 private $_response = null;
 private $_redirect = null;
 private $_logger = null;
 
 function __construct(
   $request,
   $form_generator,
   $response,
   $redirect,
   $logger
   )
 {
  $this->_request = $request;
  $this->_form_generator = $form_generator;
  $this->_response = $response;
  $this->_redirect = $redirect;
  $this->_logger = $logger;
 }
 
 public function get_response($result, $context)
 {
  // TODO: Ajaxify - v1.0
  /*if ($this->_request->is_ajax())
  {
   $this->_logger->debug('[WalkerCMS] Generating JSON response.');
   //$content = $this->_form_generator->generate_data($context->get_current_page(), $context);
   //$result['content'] = $content['contact_form']->render();
   return $this->_response->send_json($result);
  }*/
  
  $this->_logger->debug('[WalkerCMS] Generating redirect response.');
  
  return $this->_redirect->to($result['submitting_page_id'], array('form_data' => $result));
 }
}

/* End of file formresponseretriever.php */
/* Location: ./WalkerCMS/helpers/forms/formresponseretriever.php */