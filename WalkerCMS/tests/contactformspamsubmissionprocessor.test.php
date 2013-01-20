<?php
class ContactFormSpamSubmissionProcessorTest extends PHPUnit_Framework_TestCase
{
 private $_logger = null;
 private $_processor = null;
 private $_result = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_processor = new ContactFormSpamSubmissionProcessor($this->_logger);
  $this->_result = array(
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Chrome',
    'submitter_name' => 'Ingelberg Humperdink',
    'submitter_email' => 'ih@example.com',
    );
 }
 
 public function testProcess_ValidationErrorsNotSet()
 {
  $result = $this->_processor->process($this->_result, null);
  $this->assertEquals(1, count($result['validation_errors']));
 }
 
 public function testProcess_CorrectValidationErrorText()
 {
  $result = $this->_processor->process($this->_result, null);
  $this->assertEquals('Invalid entry.', $result['validation_errors'][0]);
 }
 
 public function testProcess_ValidationErrorsSet()
 {
  $this->_result['validation_errors'] = array();
  $result = $this->_processor->process($this->_result, null);
  $this->assertEquals(1, count($result['validation_errors']));
 }
 
 public function testProcess_AppendValidationErrors()
 {
  $this->_result['validation_errors'] = array('Error 1');
  $result = $this->_processor->process($this->_result, null);
  $this->assertEquals(2, count($result['validation_errors']));
 }
 
 public function testProcess_LogDebugStatements()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_processor->process($this->_result, null);
 }
 
 public function testProcess_LogErrorMessages()
 {
  $this->_logger->expects($this->atLeastOnce())->method('error');
  $result = $this->_processor->process($this->_result, null);
 }
}

/* End of file contactformspamsubmissionprocessor.test.php */
/* Location: ./WalkerCMS/tests/contactformspamsubmissionprocessor.test.php */