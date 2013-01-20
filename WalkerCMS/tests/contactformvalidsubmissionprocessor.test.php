<?php
class ContactFormValidSubmissionProcessorTest extends PHPUnit_Framework_TestCase
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
  $this->_processor = new ContactFormValidSubmissionProcessor(
    $this->_mailer,
    $this->_config,
    $this->_view,
    $this->_logger);
  $this->_result = array(
    'submitter_name' => 'Tom',
    'submitter_email' => 'tom@example.com'
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
  $new_result = $this->_result;
  $new_result['valid_input'] = true;
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form_email_body', $this->equalTo($new_result))
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
  $new_result = $this->_result;
  $new_result['valid_input'] = true;
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form_email_body', $this->equalTo($new_result))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Contact Form] Message from \'Tom\'',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('info@walkercms.net' => 'Wesley'),
                  'Rendered email message',
                  array('admin@walkercms.net' => 'Luke Skywalker'),
                  null,
                  array('tom@example.com')
                  )
                ->will($this->returnValue(array('status' => true)));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('partials.contact_form_success', $this->_context->get_contact_form_view());
 }
 
 public function testProcess_MessageFailed_SetView()
 {
  $new_result = $this->_result;
  $new_result['valid_input'] = true;
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form_email_body', $this->equalTo($new_result))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Contact Form] Message from \'Tom\'',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('info@walkercms.net' => 'Wesley'),
                  'Rendered email message',
                  array('admin@walkercms.net' => 'Luke Skywalker'),
                  null,
                  array('tom@example.com')
                  )
                ->will($this->returnValue(array('status' => false, 'submission_errors' => 'Bad stuff happened.')));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('partials.contact_form_failure', $this->_context->get_contact_form_view());
 }
 
 public function testProcess_MessageFailed_SetSubmissionErrors()
 {
  $new_result = $this->_result;
  $new_result['valid_input'] = true;
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form_email_body', $this->equalTo($new_result))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Contact Form] Message from \'Tom\'',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('info@walkercms.net' => 'Wesley'),
                  'Rendered email message',
                  array('admin@walkercms.net' => 'Luke Skywalker'),
                  null,
                  array('tom@example.com')
                  )
                ->will($this->returnValue(array('status' => false, 'submission_errors' => 'Bad stuff happened.')));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('Bad stuff happened.', $result['submission_errors']);
 }
 
 public function testProcess_MessageFailed_SetAdminEmail()
 {
  $new_result = $this->_result;
  $new_result['valid_input'] = true;
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form_email_body', $this->equalTo($new_result))
              ->will($this->returnValue($this->_view_wrapper));
  $this->_view_wrapper->expects($this->once())
                      ->method('render')
                      ->will($this->returnValue('Rendered email message'));
  $this->_mailer->expects($this->once())
                ->method('send_message')
                ->with(
                  '[Contact Form] Message from \'Tom\'',
                  array('admin@walkercms.net' => 'WalkerCMS Website'),
                  array('info@walkercms.net' => 'Wesley'),
                  'Rendered email message',
                  array('admin@walkercms.net' => 'Luke Skywalker'),
                  null,
                  array('tom@example.com')
                  )
                ->will($this->returnValue(array('status' => false, 'submission_errors' => 'Bad stuff happened.')));
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('admin@walkercms.net', $result['admin_email']);
 }
 
 public function testProcess_UseLogger()
 {
  $new_result = $this->_result;
  $new_result['valid_input'] = true;
  
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form_email_body', $this->equalTo($new_result))
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
}

/* End of file contactformvalidsubmissionprocessor.test.php */
/* Location: ./WalkerCMS/tests/contactformvalidsubmissionprocessor.test.php */