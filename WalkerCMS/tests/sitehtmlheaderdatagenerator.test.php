<?php
class SiteHTMLHeaderDataGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_generator = null;
 private $_logger = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_generator = new SiteHTMLHeaderDataGenerator($this->_logger);
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_generator->generate_data(null, null);
 }
 
 public function testGenerateData_CorrectInclusionType()
 {
  $result = $this->_generator->generate_data(null, null);
  $this->assertEquals('htmlheaders', $result['inclusion_type']);
 }
 
 public function testGenerateData_CorrectPageID()
 {
  $result = $this->_generator->generate_data(null, null);
  $this->assertEquals('site', $result['page_id']);
 }
}

/* End of file pagespecificinclusiondatagenerator.test.php */
/* Location: ./WalkerCMS/tests/pagespecificinclusiondatagenerator.test.php */