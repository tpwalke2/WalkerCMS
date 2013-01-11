<?php
class PageSpecificInclusionDataGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_generator = null;
 private $_logger = null;
 private $_page = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_page = new PageModel(array('id' => 'home'));
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_generator = new PageSpecificInclusionDataGenerator('content', $this->_logger);
  $this->_generator->generate_data($this->_page, null);
 }
 
 public function testGenerateData_CorrectInclusionType()
 {
  $this->_generator = new PageSpecificInclusionDataGenerator('content', $this->_logger);
  $result = $this->_generator->generate_data($this->_page, null);
  $this->assertEquals('content', $result['inclusion_type']);
 }
 
 public function testGenerateData_DifferentInclusionType()
 {
  $this->_generator = new PageSpecificInclusionDataGenerator('subnav', $this->_logger);
  $result = $this->_generator->generate_data($this->_page, null);
  $this->assertEquals('subnav', $result['inclusion_type']);
 }
 
 public function testGenerateData_CorrectPageID()
 {
  $this->_generator = new PageSpecificInclusionDataGenerator('content', $this->_logger);
  $result = $this->_generator->generate_data($this->_page, null);
  $this->assertEquals('home', $result['page_id']);
 }
 
 public function testGenerateData_DifferentPageID()
 {
  $this->_page = new PageModel(array('id' => 'contact'));
  $this->_generator = new PageSpecificInclusionDataGenerator('content', $this->_logger);
  $result = $this->_generator->generate_data($this->_page, null);
  $this->assertEquals('contact', $result['page_id']);
 }
}

/* End of file pagespecificinclusiondatagenerator.test.php */
/* Location: ./WalkerCMS/tests/pagespecificinclusiondatagenerator.test.php */