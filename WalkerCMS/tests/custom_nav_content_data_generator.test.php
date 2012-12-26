<?php
require_once(path('app') . 'models/page_model.php');
require_once(path('app') . 'helpers/custom_nav_content_data_generator.php');

class TestCustomNavContentDataGenerator extends PHPUnit_Framework_TestCase
{
 private $_page = null;
 private $_generator = null;
 
 protected function setUp()
 {
  $this->_page = new PageModel(array('id' => 'home'));
  $this->_generator = new CustomNavContentDataGenerator('nav');
 }
 
 public function testCorrectPageID()
 {
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('home', $result['page_id']);
 }
 
 public function testDifferentPageID()
 {
  $this->_page = new PageModel(array('id' => 'about'));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('about', $result['page_id']);
 }
 
 public function testCorrectInclusionType()
 {
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('nav', $result['inclusion_type']);
 }
 
 public function testDifferentInclusionType()
 {
  $this->_generator = new CustomNavContentDataGenerator('subnav');
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('subnav', $result['inclusion_type']);
 }
}

/* End of file custom_nav_content_data_generator.test.php */
/* Location: ./WalkerCMS/tests/custom_nav_content_data_generator.test.php */