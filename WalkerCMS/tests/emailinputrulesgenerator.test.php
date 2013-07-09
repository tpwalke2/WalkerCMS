<?php
class EmailInputRulesGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_generator = null;
 private $_item = null;
 
 protected function setUp()
 {
  $this->_generator = new EmailInputRulesGenerator();
  $this->_item = array();
 }
 
 public function testGetRules_Required()
 {
  $this->_item['required'] = true;
  $this->assertEquals('required|email', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_NotRequired()
 {
  $this->_item['required'] = false;
  $this->assertEquals('email', $this->_generator->get_rules($this->_item));
 }
}

/* End of file emailinputrulesgenerator.test.php */
/* Location: ./WalkerCMS/tests/emailinputrulesgenerator.test.php */