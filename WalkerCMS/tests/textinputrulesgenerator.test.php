<?php
class TextInputRulesGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_generator = null;
 private $_item = null;
 
 protected function setUp()
 {
  $this->_generator = new TextInputRulesGenerator();
  $this->_item = array();
 }
 
 public function testGetRules_Required()
 {
  $this->_item['required'] = true;
  $this->assertEquals('required', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_MaxLength()
 {
  $this->_item['max_length'] = 1;
  $this->assertEquals('max:1', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_RequiredAndMaxLength()
 {
  $this->_item['required'] = true;
  $this->_item['max_length'] = 42;
  $this->assertEquals('required|max:42', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_NotRequired_NoMaxLength()
 {
  $this->_item['required'] = false;
  $this->assertEquals('', $this->_generator->get_rules($this->_item));
 }
}

/* End of file textinputrulesgenerator.test.php */
/* Location: ./WalkerCMS/tests/textinputrulesgenerator.test.php */