<?php
class ChainedConfigValidatorTest extends PHPUnit_Framework_TestCase
{
 private $_inner_validator1 = null;
 private $_inner_validator2 = null;
 private $_validator = null;
 private $_config = null;
 
 protected function setUp()
 {
  $this->_inner_validator1 = $this->getMock('IConfigValidator', array('validate'));
  $this->_inner_validator2 = $this->getMock('IConfigValidator', array('validate'));
  
  $this->_config = array();
 }
 
 public function testValidate_NoInnerValidators()
 {
  $this->_validator = new ChainedConfigValidator();
  
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_OneInnerValidator_NoErrors()
 {
  $this->_inner_validator1->expects($this->once())
                          ->method('validate')
                          ->with($this->equalTo($this->_config))
                          ->will($this->returnValue(array('valid' => true, 'errors' => array())));
  
  $this->_validator = new ChainedConfigValidator($this->_inner_validator1);
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_TwoInnerValidators_BothNoErrors()
 {
  $this->_inner_validator1->expects($this->once())
                          ->method('validate')
                          ->with($this->equalTo($this->_config))
                          ->will($this->returnValue(array('valid' => true, 'errors' => array())));
  
  $this->_inner_validator2->expects($this->once())
                          ->method('validate')
                          ->with($this->equalTo($this->_config))
                          ->will($this->returnValue(array('valid' => true, 'errors' => array())));
 
  $this->_validator = new ChainedConfigValidator($this->_inner_validator1, $this->_inner_validator2);
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_TwoInnerValidators_BothHaveErrors()
 {
  $errors1 = array(
    'valid' => false,
    'errors' => array('error 1.1', 'error 1.2'),
  );
  $this->_inner_validator1->expects($this->once())
                          ->method('validate')
                          ->with($this->equalTo($this->_config))
                          ->will($this->returnValue($errors1));
 
  $errors2 = array(
    'valid' => false,
    'errors' => array('error 2.1', 'error 2.2')
  );
  $this->_inner_validator2->expects($this->once())
                          ->method('validate')
                          ->with($this->equalTo($this->_config))
                          ->will($this->returnValue($errors2));
 
  $this->_validator = new ChainedConfigValidator($this->_inner_validator1, $this->_inner_validator2);
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals(4, count($result['errors']));
  $this->assertEquals('error 1.1', $result['errors'][0]);
  $this->assertEquals('error 1.2', $result['errors'][1]);
  $this->assertEquals('error 2.1', $result['errors'][2]);
  $this->assertEquals('error 2.2', $result['errors'][3]);
 }
}

/* End of file chainedconfigvalidator.test.php */
/* Location: ./WalkerCMS/tests/chainedconfigvalidator.test.php */