<?php
require_once(path('app') . 'helpers/page_specific_inclusion_data_generator.php');
require_once(path('app') . 'models/page_model.php');

class TestPageSpecificInclusionDataGenerator extends PHPUnit_Framework_TestCase
{
 private $_generator = null;
 private $_page = null;
 
 protected function setUp()
 {
  $this->_page = new PageModel(array('id' => 'home'));
 }
 
 public function testGenerateData_CorrectInclusionType()
 {
  $this->_generator = new PageSpecificInclusionDataGenerator('content');
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('content', $result['inclusion_type']);
 }
 
 public function testGenerateData_DifferentInclusionType()
 {
  $this->_generator = new PageSpecificInclusionDataGenerator('subnav');
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('subnav', $result['inclusion_type']);
 }
 
 public function testGenerateData_CorrectPageID()
 {
  $this->_generator = new PageSpecificInclusionDataGenerator('content');
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('home', $result['page_id']);
 }
 
 public function testGenerateData_DifferentPageID()
 {
  $this->_page = new PageModel(array('id' => 'contact'));
  $this->_generator = new PageSpecificInclusionDataGenerator('content');
  $result = $this->_generator->generate_data(null, $this->_page, null);
  $this->assertEquals('contact', $result['page_id']);
 }
}

/* End of file page_specific_inclusion_data_generator.test.php */
/* Location: ./WalkerCMS/tests/page_specific_inclusion_data_generator.test.php */