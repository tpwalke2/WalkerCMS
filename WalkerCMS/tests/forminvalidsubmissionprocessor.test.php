<?php
class FormInvalidSubmissionProcessorTest extends PHPUnit_Framework_TestCase
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
  $this->_processor = new FormInvalidSubmissionProcessor($this->_logger);
  $this->_result = array('form' => array('indexed_items' => array()));
  $this->_error_expectations = array();
  $this->_validation = $this->getMock('IValidationWrapper', array('fails', 'has_errors', 'get_errors', 'get_all_errors'));
  $this->_validation->expects($this->any())
                    ->method('has_errors')
                    ->will($this->returnCallback(array($this, 'has_errors_callback')));
  $this->_validation->expects($this->any())
                    ->method('get_errors')
                    ->will($this->returnCallback(array($this, 'get_errors_callback')));
  $this->_validation->expects($this->any())
       ->method('get_all_errors')
       ->will($this->returnCallback(array($this, 'get_all_errors_callback')));
  $this->_context = new AppContext();
  $this->_context->set_form_validation($this->_validation);
 }
 
 public function has_errors_callback($key)
 {
  return isset($this->_error_expectations[$key]);
 }
 
 public function get_errors_callback($key)
 {
  return $this->_error_expectations[$key];
 }
 
 public function get_all_errors_callback()
 {
  $result = array();
  
  foreach ($this->_error_expectations as $error)
  {
   $result[] = implode("\n", $error);
  }
  
  return $result;
 }
 
 public function testProcess_NoFormItemsNoErrors()
 {
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertEquals(0, count($result['item_errors']));
 }
 
 public function testProcess_OneFormItem_NoErrors()
 {
  $this->_result['form']['indexed_items']['item1'] = array('id' => 'item1', 'input_name' => 'data_section_item1', 'fully_qualified_id' => 'section_item1');
  $result = $this->_processor->process($this->_result, $this->_context);
 
  $this->assertEquals(0, count($result['item_errors']));
 }
 
 public function testProcess_MultipleFormItems_NoErrors()
 {
  $this->_result['form']['indexed_items']['item1'] = array('id' => 'item1', 'input_name' => 'data_section_item1', 'fully_qualified_id' => 'section_item1');
  $this->_result['form']['indexed_items']['item2'] = array('id' => 'item2', 'input_name' => 'data_section_item2', 'fully_qualified_id' => 'section_item2');
  $result = $this->_processor->process($this->_result, $this->_context);
 
  $this->assertEquals(0, count($result['item_errors']));
 }
 
 public function testProcess_OneFormItemHasError()
 {
  $this->_error_expectations['data_section_item1'] = array('item1 error');
  $this->_result['form']['indexed_items']['item1'] = array('id' => 'item1', 'input_name' => 'data_section_item1', 'fully_qualified_id' => 'section_item1');
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertEquals(1, count($result['item_errors']));
  $this->assertTrue($result['item_errors']['section_item1'] != '');
 }
 
 public function testProcess_MultipleFormItemsHaveError()
 {
  $this->_error_expectations['data_section_item1'] = array('item1 error');
  $this->_error_expectations['data_section_item2'] = array('item2 error');
  $this->_result['form']['indexed_items']['item1'] = array('id' => 'item1', 'input_name' => 'data_section_item1', 'fully_qualified_id' => 'section_item1');
  $this->_result['form']['indexed_items']['item2'] = array('id' => 'item2', 'input_name' => 'data_section_item2', 'fully_qualified_id' => 'section_item2');
  $result = $this->_processor->process($this->_result, $this->_context);
  
  $this->assertEquals(2, count($result['item_errors']));
  $this->assertTrue($result['item_errors']['section_item1'] != '');
  $this->assertTrue($result['item_errors']['section_item2'] != '');
 }
 
 public function testProcess_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_processor->process($this->_result, $this->_context);
 }
}
/* End of file forminvalidsubmissionprocessor.test.php */
/* Location: ./WalkerCMS/tests/forminvalidsubmissionprocessor.test.php */