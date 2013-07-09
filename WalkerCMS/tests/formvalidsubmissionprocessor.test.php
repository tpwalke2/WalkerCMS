<?php
class FormValidSubmissionProcessorTest extends PHPUnit_Framework_TestCase
{
 private $_mailer = null;
 private $_config = null;
 private $_view = null;
 private $_view_wrapper = null;
 private $_logger = null;
 private $_processor = null;
 private $_config_expectations = null;
 private $_result = null;
 private $_context = null;
 
 protected function setUp()
 {
  $this->_mailer = $this->getMock('IMailerAdapter', array('send_message'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config->expects($this->any())
                ->method('get')
                ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_view = $this->getMock('IViewAdapter', array('generate_view'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_processor = new FormValidSubmissionProcessor(
    $this->_mailer,
    $this->_config,
    $this->_view,
    $this->_logger);
  $this->_result = array(
    'spam_control'  => '',
    'ip_address'    => '127.0.0.1',
    'user_agent'    => 'a user agent',
    'form' => array(
      'id'              => 'information',
      'description'     => 'Information',
      'recipient_email' => 'user@example.com',
      'recipient_name'  => 'Recipient',
      'indexed_items'   => array(),
      ),
    'item_values' => array(),
  );
  $this->_context = new AppContext();
  $this->_config_expectations = array(
    'walkercms.admin_email' => 'admin@walkercms.net',
    'walkercms.organization_name' => 'WalkerCMS',
    'walkercms.contact_email' => 'info@walkercms.net',
    'walkercms.contact_name' => 'Wesley',
    'walkercms.admin_name' => 'Luke Skywalker',
    );
  $this->_view_wrapper = $this->getMock('IViewWrapper', array('render'));
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
 }
 
 public function testProcess_ValidInputSet()
 {
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(),
    );
    
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.email_body', $this->equalTo($data))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->will($this->returnValue(array('status' => true)));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertTrue($result['valid_input']);
 }
 
 public function testProcess_MessageSent_SetView()
 {
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(),
  );
    
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.email_body', $this->equalTo($data))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Information] Data submission',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('user@example.com' => 'Recipient'),
                  'Rendered email message'
                  )
                ->will($this->returnValue(array('status' => true)));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('forms.submit_success', $this->_context->get_form_result_view());
 }
 
 public function testProcess_MessageFailed_SetView()
 {
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(),
  );
    
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.email_body', $this->equalTo($data))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Information] Data submission',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('user@example.com' => 'Recipient'),
                  'Rendered email message'
                  )
                ->will($this->returnValue(array('status' => false, 'submission_errors' => 'Bad stuff happened.')));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('forms.submit_failure', $this->_context->get_form_result_view());
 }
 
 public function testProcess_MessageFailed_SetSubmissionErrors()
 {
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(),
  );
    
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.email_body', $this->equalTo($data))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Information] Data submission',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('user@example.com' => 'Recipient'),
                  'Rendered email message'
                  )
                ->will($this->returnValue(array('status' => false, 'submission_errors' => 'Bad stuff happened.')));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('Bad stuff happened.', $result['submission_errors']);
 }
 
 public function testProcess_MessageFailed_SetAdminEmail()
 {
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(),
  );
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.email_body', $this->equalTo($data))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Information] Data submission',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('user@example.com' => 'Recipient'),
                  'Rendered email message'
                  )
                ->will($this->returnValue(array('status' => false, 'submission_errors' => 'Bad stuff happened.')));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('admin@walkercms.net', $result['admin_email']);
 }
 
 public function testProcess_UseLogger()
 {
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(),
  );
    
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.email_body', $this->equalTo($data))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->will($this->returnValue(array('status' => true)));
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_processor->process($this->_result, $this->_context);
 }
 
 public function testProcess_PopulateItemsInEmail()
 {
  $this->_result['form']['indexed_items'] = array(
     'section_item1' => array(
        'id' => 'item1',
        'description' => 'Item 1',
        'input_name'  => 'section_item1',
       ),
    'section_item2' => array(
      'id' => 'item2',
      'description' => 'Item 2',
      'input_name'  => 'section_item2',
    ),
    'section_item3' => array(
      'id' => 'item3',
      'description' => 'Item 3',
      'input_name'  => 'section_item3',
    ),
    'section_item4' => array(
      'id' => 'item4',
      'description' => 'Item 4',
      'input_name'  => 'section_item4',
    ),
    'section_item5' => array(
      'id' => 'item5',
      'description' => 'Item 5',
      'input_name'  => 'section_item5',
    ),
  );
  $this->_result['item_values'] = array(
    'section_item2' => '',
    'section_item3' => ' ',
    'section_item4' => 'A value',
    'section_item5' => ' a value with spaces surrounding ',
  );
  
  $data = array(
    'form_id'          => 'information',
    'form_description' => 'Information',
    'spam_control'     => '',
    'ip_address'       => '127.0.0.1',
    'user_agent'       => 'a user agent',
    'input_items'      => array(
      array('description' => 'Item 1', 'value' => '[NO ENTRY]'),
      array('description' => 'Item 2', 'value' => '[NO ENTRY]'),
      array('description' => 'Item 3', 'value' => '[NO ENTRY]'),
      array('description' => 'Item 4', 'value' => 'A value'),
      array('description' => 'Item 5', 'value' => 'a value with spaces surrounding'),
    ),
  );
 
  $this->_view->expects($this->once())
       ->method('generate_view')
       ->with('forms.email_body', $this->equalTo($data))
       ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
       ->method('render')
       ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
       ->method('send_message')
       ->with(
         '[Information] Data submission',
         array('admin@walkercms.net' => 'WalkerCMS Website'),
         array('user@example.com' => 'Recipient'),
         'Rendered email message'
        )
       ->will($this->returnValue(array('status' => true)));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('forms.submit_success', $this->_context->get_form_result_view());
 }
}

/* End of file forminvalidsubmissionprocessor.test.php */
/* Location: ./WalkerCMS/tests/forminvalidsubmissionprocessor.test.php */