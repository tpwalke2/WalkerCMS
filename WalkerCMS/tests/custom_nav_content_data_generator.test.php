<?php
require_once(path('app') . 'models/page_model.php');
require_once(path('app') . 'helpers/interfaces/logger_adapter.php');
require_once(path('app') . 'helpers/custom_nav_content_data_generator.php');

class TestCustomNavContentDataGenerator extends PHPUnit_Framework_TestCase
{
 private $_page = null;
 private $_logger = null;
 private $_generator = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_page = new PageModel(array('id' => 'home'));
  $this->_generator = new CustomNavContentDataGenerator('nav', $this->_logger);
 }
 
 public function testCorrectPageID()
 {
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('home', $result['page_id']);
 }
 
 public function testDifferentPageID()
 {
  $this->_page = new PageModel(array('id' => 'about'));
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('about', $result['page_id']);
 }
 
 public function testCorrectInclusionType()
 {
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('nav', $result['inclusion_type']);
 }
 
 public function testDifferentInclusionType()
 {
  $this->_generator = new CustomNavContentDataGenerator('subnav', $this->_logger);
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('subnav', $result['inclusion_type']);
 }
}

/* End of file custom_nav_content_data_generator.test.php */
/* Location: ./WalkerCMS/tests/custom_nav_content_data_generator.test.php */