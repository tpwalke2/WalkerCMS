<?php
use Laravel\Response;

class Contact_Controller extends Base_Controller
{
 public $restful = true;
 
 private $_pages_retriever = null;
 private $_contact_form_generator = null;
 private $_config = null;
 private $_input = null;
 private $_validator = null;
 private $_redirect = null;
 private $_request = null;
 private $_response = null;
 private $_view = null;
 private $_mailer = null;
 private $_logger = null;
 
 public function __construct(
   $pages_retriever,
   $contact_form_generator,
   $config,
   $input,
   $validator,
   $redirect,
   $request,
   $response,
   $view,
   $mailer,
   $logger)
 {
  parent::__construct();
  $this->_pages_retriever = $pages_retriever;
  $this->_contact_form_generator = $contact_form_generator;
  $this->_config = $config;
  $this->_input = $input;
  $this->_validator = $validator;
  $this->_redirect = $redirect;
  $this->_request = $request;
  $this->_response = $response;
  $this->_view = $view;
  $this->_mailer = $mailer;
  $this->_logger = $logger;
 }
 
 public function post_contact_submission()
 {
  // TODO: cleanup and unit test the contact form controller
  $this->_logger->debug('[Contact Form] Processing contact form.');
  $context = new AppContext();
   
  $result = array(
    'submitting_page_id' => $this->_input->get('page_id', $this->_config->get('walkercms.contact_page')),
    'submitter_name'     => $this->_input->get('name', ''),
    'submitter_email'    => $this->_input->get('email', ''),
    'message'            => $this->_input->get('message', ''),
    'valid_input'        => true,
    'organization_name'  => $this->_config->get('walkercms.organization_name'),
   );
  
  $rules = array(
    'name'    => 'required|max:50',
    'email'   => 'required|max:255|email',
    'message' => 'required|max:1000'
  );
 
  $spam_control = $this->_input->get('required_control', '');
  $ip_address = $this->_request->ip_address();
  $user_agent = $this->_request->user_agent();
 
  $validation = $this->_validator->create_validator($this->_input->all(), $rules);
 
  if ($validation->fails())
  {
   $this->_logger->debug('[Contact Form] Input validation failed');
   
   $this->add_validation_error($validation->errors, 'name', $result);
   $this->add_validation_error($validation->errors, 'email', $result);
   $this->add_validation_error($validation->errors, 'message', $result);
   $result['valid_input'] = false;
  }
  elseif ($spam_control != '')
  {
   $this->_logger->error('[Contact Form] Potential form spammer identified');
   $this->_logger->error("[Contact Form] IP Address: $ip_address");
   $this->_logger->error("[Contact Form] User Agent: $user_agent");
   $this->_logger->error("[Contact Form] Name: {$result['submitter_name']}");
   $this->_logger->error("[Contact Form] Email: {$result['submitter_email']}");
   $result['validation_errors'][] = 'Invalid entry.';
   $result['valid_input'] = false;
  }
  else
  {
   $this->_logger->debug('[WalkerCMS] Valid contact form submission');
   $message_data = array(
     'submitter_name'       => $result['submitter_name'],
     'submitter_email'      => $result['submitter_email'],
     'message'              => $result['message'],
     'required_control'     => $spam_control,
     'submitter_ip'         => $ip_address,
     'submitter_user_agent' => $user_agent
   );
 
   $message_body = $this->_view->generate_view('partials.contact_form_email_body', $message_data)->render();
   $send_result = $this->_mailer->send_message(
     "[Contact Form] Message from '{$message_data['submitter_name']}'",
     array($this->_config->get('walkercms.admin_email') => $this->_config->get('walkercms.organization_name') . ' Website'),
     array($this->_config->get('walkercms.contact_email') => $this->_config->get('walkercms.contact_name')),
     $message_body,
     array($this->_config->get('walkercms.admin_email') => $this->_config->get('walkercms.admin_name')),
     null,
     array($message_data['submitter_email'])
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
    $result['submission_errors'] = $status['submission_errors'];
    $result['admin_email'] = $this->_config->get('walkercms.admin_email');
   }
  }
 
  $context->set_contact_form_data($result);
  
  if ($this->_request->is_ajax())
  {
   $this->_logger->debug('[Contact Form] Generating JSON response.');
   $pages = $this->_pages_retriever->get_pages();
   $context->set_pages($pages);
   $context->set_current_page($pages[$result['submitting_page_id']]);
   $content = $this->_contact_form_generator->generate_data($context->get_current_page(), $context);
   $result['content'] = $content['contact_form']->render();
   return $this->_response->send_json($result);
  }
  
  return $this->_redirect->to($result['submitting_page_id'], array('context' => $context));
 }
 
 private function add_validation_error($errors, $errors_key, &$data)
 {
  if ($errors->has($errors_key))
  {
   $data[$errors_key . '_validation_error'] = join('&nbsp;', $errors->get($errors_key));
  }
 }
}
/* End of file contact.php */
/* Location: ./WalkerCMS/controllers/contact.php */