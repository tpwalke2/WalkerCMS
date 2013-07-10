<?php
class MultipleChoiceRulesGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_generator = null;
 private $_item = null;
 
 protected function setUp()
 {
  $this->_generator = new MultipleChoiceRulesGenerator();
  $this->_item = array();
 }
 
 public function testGetRules_Required_UnsetChoices()
 {
  $this->_item['required'] = true;
  $this->assertEquals('required', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_Required_EmptyChoices()
 {
  $this->_item['required'] = true;
  $this->_item['choices'] = array();
  $this->assertEquals('required', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_NotRequired_NoChoices()
 {
  $this->_item['required'] = false;
  $this->assertEquals('', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_OneChoice()
 {
  $this->_item['choices'] = array('choice1' => 'choice1');
  $this->assertEquals('in:0,choice1', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_RequiredNotSet_TwoChoices()
 {
  $this->_item['choices'] = array('choice1' => 'choice1', 'choice2' => 'choice2');
  $this->assertEquals('in:0,choice1,choice2', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_NotRequired_TwoChoices()
 {
  $this->_item['required'] = false;
  $this->_item['choices'] = array('choice1' => 'choice1', 'choice2' => 'choice2');
  $this->assertEquals('in:0,choice1,choice2', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_RequiredAndTwoChoices()
 {
  $this->_item['required'] = true;
  $this->_item['choices'] = array('choice1' => 'choice1', 'choice2' => 'choice2');
  $this->assertEquals('required|in:choice1,choice2', $this->_generator->get_rules($this->_item));
 }
 
 public function testGetRules_UseKeys()
 {
  $this->_item['required'] = true;
  $this->_item['choices'] = array('choice1' => 'Choice 1', 'choice2' => 'Choice 2');
  $this->assertEquals('required|in:choice1,choice2', $this->_generator->get_rules($this->_item));
 }
}

/* End of file multiplechoicerulesgenerator.test.php */
/* Location: ./WalkerCMS/tests/multiplechoicerulesgenerator.test.php */