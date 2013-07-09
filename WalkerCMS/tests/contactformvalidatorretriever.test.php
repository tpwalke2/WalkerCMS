<?php
class ContactFormValidatorRetrieverTest extends PHPUnit_Framework_TestCase
{
 private $_validator = null;
 private $_input = null;
 private $_logger = null;
 private $_retriever = null;
 
 protected function setUp()
 {
  $this->_validator = $this->getMock('IValidatorAdapter', array('create_validator'));
  $this->_input = $this->getMock('IInputAdapter', array('all', 'get'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_retriever = new ContactFormValidatorRetriever($this->_validator, $this->_input, $this->_logger);
 }
 
 public function testGetValidator()
 {
  $rules = array(
    'name' => 'required|max:50',
    'email' => 'required|max:255|email',
    'message' => 'required|max:2000');
  $all_input = array('all_input');
  $this->_input->expects($this->once())
               ->method('all')
               ->will($this->returnValue($all_input));
  $validation = array('validation');
  $this->_validator->expects($this->once())
                   ->method('create_validator')
                   ->with($this->equalTo($all_input), $this->equalTo($rules))
                   ->will($this->returnValue($validation));
  $this->assertSame($validation, $this->_retriever->get_form_validator());
 }
 
 public function testGetValidator_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $rules = array(
    'name' => 'required|max:50',
    'email' => 'required|max:255|email',
    'message' => 'required|max:2000');
  $all_input = array('all_input');
  $this->_input->expects($this->once())
               ->method('all')
               ->will($this->returnValue($all_input));
  $validation = array('validation');
  $this->_validator->expects($this->once())
                   ->method('create_validator')
                   ->with($this->equalTo($all_input), $this->equalTo($rules))
                   ->will($this->returnValue($validation));
  $result = $this->_retriever->get_form_validator();
 }
}

/* End of file contactformvalidatorretriever.test.php */
/* Location: ./WalkerCMS/tests/contactformvalidatorretriever.test.php */