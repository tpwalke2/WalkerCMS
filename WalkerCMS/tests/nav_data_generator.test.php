<?php
require_once(path('app') . 'helpers/nav_item_converter.php');
require_once(path('app') . 'helpers/interfaces/page_matcher.php');
require_once(path('app') . 'helpers/interfaces/page_retriever.php');
require_once(path('app') . 'helpers/interfaces/config_adapter.php');
require_once(path('app') . 'helpers/interfaces/logger_adapter.php');
require_once(path('app') . 'models/page_model.php');

class TestNavDataGenerator extends PHPUnit_Framework_TestCase
{
 private $_nav_item_converter = null;
 private $_page_matcher = null;
 private $_parent_retriever = null;
 private $_config_adapter = null;
 private $_logger = null;
 private $_generator = null;
 private $_current_page = null;
 private $_pages = null;
 
 protected function setUp()
 {
  $this->_nav_item_converter = $this->getMock('NavItemConverter', array('convert'), array(null));
  $this->_page_matcher = $this->getMock('IPageMatcher', array('is_match'));
  $this->_parent_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_config_adapter = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_current_page = new PageModel(array('id' => 'home'));
  $this->_pages = array();
  
  $this->_generator = new NavDataGenerator($this->_nav_item_converter, $this->_page_matcher, $this->_parent_retriever, $this->_config_adapter, true, $this->_logger);
 }
 
 public function testGenerateData_NavID_Nav()
 {
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
  
  $this->assertEquals('nav', $result['nav_id']);
 }
 
 public function testGenerateData_NavID_SubNav()
 {
  $this->_generator = new NavDataGenerator($this->_nav_item_converter, $this->_page_matcher, $this->_parent_retriever, $this->_config_adapter, false, $this->_logger);
  
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
  
  $this->assertEquals('subNav', $result['nav_id']);
 }
 
 public function testGenerateData_IsPrimaryNav_Nav()
 {
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
 
  $this->assertTrue($result['is_primary_nav']);
 }
 
 public function testGenerateData_IsPrimaryNav_SubNav()
 {
  $this->_generator = new NavDataGenerator($this->_nav_item_converter, $this->_page_matcher, $this->_parent_retriever, $this->_config_adapter, false, $this->_logger);
 
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
 
  $this->assertFalse($result['is_primary_nav']);
 }
 
 public function testGenerateData_OrganizationName()
 {
  $this->_config_adapter->expects($this->once())
                        ->method('get')
                        ->with('walkercms.organization_name')
                        ->will($this->returnValue('WalkerCMS'));
  
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
  
  $this->assertEquals('WalkerCMS', $result['organization_name']);
 }
 
 public function testGenerateData_DifferentOrganizationName()
 {
  $this->_config_adapter->expects($this->once())
                        ->method('get')
                        ->with('walkercms.organization_name')
                        ->will($this->returnValue('Northwind'));
 
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
 
  $this->assertEquals('Northwind', $result['organization_name']);
 }
 
 public function testGenerateData_NoNavItems()
 {
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
  
  $this->assertEquals(0, count($result['nav_items']));
 }
 
 public function testGenerateData_OneMatchedPage()
 {
  $this->_pages['home'] = $this->_current_page;
  $nav_data = array('page_id' => 'home');
  
  $this->_parent_retriever->expects($this->once())
                          ->method('get_page')
                          ->with($this->_pages, $this->_current_page)
                          ->will($this->returnValue(null));
  $this->_page_matcher->expects($this->once())
                      ->method('is_match')
                      ->with($this->_current_page, null)
                      ->will($this->returnValue(true));
  $this->_nav_item_converter->expects($this->once())
                            ->method('convert')
                            ->with($this->_pages, $this->_current_page, $this->_current_page)
                            ->will($this->returnValue($nav_data));
  
  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
 
  $this->assertEquals(1, count($result['nav_items']));
  $this->assertEquals('home', $result['nav_items'][0]['page_id']);
 }
 
 public function testGenerateData_OneUnMatchedPage()
 {
  $this->_pages['home'] = $this->_current_page;
  
  $this->_parent_retriever->expects($this->once())
                          ->method('get_page')
                          ->with($this->_pages, $this->_current_page)
                          ->will($this->returnValue(null));
  $this->_page_matcher->expects($this->once())
                      ->method('is_match')
                      ->with($this->_current_page, null)
                      ->will($this->returnValue(false));

  $result = $this->_generator->generate_data($this->_pages, $this->_current_page, null);
 
  $this->assertEquals(0, count($result['nav_items']));
 }
}

/* End of file nav_data_generator.test.php */
/* Location: ./WalkerCMS/tests/nav_data_generator.test.php */