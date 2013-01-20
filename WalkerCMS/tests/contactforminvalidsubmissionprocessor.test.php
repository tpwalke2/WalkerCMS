<?php
class ContactFormInvalidSubmissionProcessorTest extends PHPUnit_Framework_TestCase
{
 private $_logger = null;
 private $_processor = null;
 private $_result = null;
 private $_context = null;
 private $_validation = null;
 private $_error_expectations = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_processor = new ContactFormInvalidSubmissionProcessor($this->_logger);
  $this->result = array();
  $this->_error_expectations = array();
  $this->_validation = $this->getMock('IValidationWrapper', array('fails', 'has_errors', 'get_errors'));
  $this->_validation->expects($this->any())
                    ->method('has_errors')
                    ->will($this->returnCallback(array($this, 'has_errors_callback')));
  $this->_validation->expects($this->any())
                    ->method('get_errors')
                    ->will($this->returnCallback(array($this, 'get_errors_callback')));
  $this->_context = new AppContext();
  $this->_context->set_contact_validation($this->_validation);
 }
 
 public function has_errors_callback($key)
 {
  return isset($this->_error_expectations[$key]);
 }
 
 public function get_errors_callback($key)
 {
  return $this->_error_expectations[$key];
 }
 
 public function testProcess_NoErrors()
 {
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertFalse(isset($result['name_validation_error']));
  $this->assertFalse(isset($result['email_validation_error']));
  $this->assertFalse(isset($result['email_validation_error']));
 }
 
 public function testProcess_NameError()
 {
  $this->_error_expectations['name'] = array('name error');
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertTrue(isset($result['name_validation_error']));
  $this->assertFalse(isset($result['email_validation_error']));
  $this->assertFalse(isset($result['message_validation_error']));
 }
 
 public function testProcess_EmailError()
 {
  $this->_error_expectations['email'] = array('email error');
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertFalse(isset($result['name_validation_error']));
  $this->assertTrue(isset($result['email_validation_error']));
  $this->assertFalse(isset($result['message_validation_error']));
 }
 
 public function testProcess_MessageError()
 {
  $this->_error_expectations['message'] = array('message error');
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertFalse(isset($result['name_validation_error']));
  $this->assertFalse(isset($result['email_validation_error']));
  $this->assertTrue(isset($result['message_validation_error']));
 }
 
 public function testProcess_MergeNameErrors()
 {
  $this->_error_expectations['name'] = array('This is one error.', 'This is another error.');
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertEquals('This is one error.&nbsp;This is another error.', $result['name_validation_error']);
 }
 
 public function testProcess_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())
                ->method('debug');
  $result = $this->_processor->process($this->_result, $this->_context);
 }
}

/* End of file contactforminvalidsubmissionprocessor.test.php */
/* Location: ./WalkerCMS/tests/contactforminvalidsubmissionprocessor.test.php */