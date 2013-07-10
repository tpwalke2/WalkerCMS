<?php
class FormSpamSubmissionProcessorTest extends PHPUnit_Framework_TestCase
{
 private $_logger = null;
 private $_processor = null;
 private $_result = null;
 private $_context = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_processor = new FormSpamSubmissionProcessor($this->_logger);
  $this->_result = array(
    'ip_address'   => '127.0.0.1',
    'user_agent'   => 'Chrome',
    'spam_control' => 'some_data',
    'form'         => array('id' => 'info'),
  );
  $this->_context = new AppContext();
 }
 
 public function testProcess_ValidationErrorsNotSet()
 {
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals(1, count($result['validation_errors']));
 }
 
 public function testProcess_CorrectValidationErrorText()
 {
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('Invalid entry.', $result['validation_errors'][0]);
 }
 
 public function testProcess_ValidationErrorsSet()
 {
  $this->_result['validation_errors'] = array();
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals(1, count($result['validation_errors']));
 }
 
 public function testProcess_AppendValidationErrors()
 {
  $this->_result['validation_errors'] = array('Error 1');
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals(2, count($result['validation_errors']));
 }
 
 public function testProcess_LogDebugStatements()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_processor->process($this->_result, $this->_context);
 }
 
 public function testProcess_LogErrorMessages()
 {
  $this->_logger->expects($this->atLeastOnce())->method('error');
  $result = $this->_processor->process($this->_result, $this->_context);
 }
 
 public function testProcess_SetFormResultView()
 {
  $result = $this->_processor->process($this->_result, $this->_context);
  $this->assertEquals('forms.submit_failure', $this->_context->get_form_result_view());
 }
}
/* End of file formspamsubmissionprocessor.test.php */
/* Location: ./WalkerCMS/tests/formspamsubmissionprocessor.test.php */