<?php
require_once(path('app') . 'helpers/interfaces/page_retriever.php');
require_once(path('app') . 'helpers/interfaces/logger_adapter.php');
require_once(path('app') . 'helpers/content_source_page_retriever.php');
require_once(path('app') . 'models/page_model.php');

class TestContentSourcePageRetriever extends PHPUnit_Framework_TestCase
{
 private $_first_child_retriever = null;
 private $_logger = null;
 private $_retriever = null;
 private $_pages = null;
 private $_current_page = null;
 private $_first_child_page = null;
 
 protected function setUp()
 {
  $this->_first_child_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_retriever = new ContentSourcePageRetriever($this->_first_child_retriever, $this->_logger);
  
  $this->_pages = array();
  $this->_current_page = $this->getMock('PageModel',
                                        array('has_content'),
                                        array(array('id' => 'home',
                                                    'sub_nav_on_page' => true)));
  $this->_first_child_page = new PageModel(array('id' => 'faq'));
 }
 
 public function testGetPage_NoSubNavOnPage_ContentIsAvailable()
 {
  $this->_first_child_retriever->expects($this->never())
                               ->method('get_page');
  
  $this->_current_page = $this->getMock('PageModel',
                                        array('has_content'),
                                        array(array('id' => 'home',
                                                    'sub_nav_on_page' => false)));
  $this->_current_page->expects($this->any())
                      ->method('has_content')
                      ->will($this->returnValue(true));
  
  $this->assertSame($this->_current_page, $this->_retriever->get_page($this->_pages, $this->_current_page));
 }
 
 public function testGetPage_SubNavOnPage_ContentNotAvailable()
 {
  $this->_first_child_retriever->expects($this->once())
                               ->method('get_page')
                               ->with($this->_pages, $this->_current_page)
                               ->will($this->returnValue($this->_first_child_page));
  $this->_current_page->expects($this->any())
                      ->method('has_content')
                      ->will($this->returnValue(false));
  $this->assertSame($this->_first_child_page, $this->_retriever->get_page($this->_pages, $this->_current_page));
 }
 
 public function testGetPage_SubNavOnPage_ContentAvailable()
 {
  $this->_first_child_retriever->expects($this->never())
                               ->method('get_page');
  $this->_current_page->expects($this->any())
                      ->method('has_content')
                      ->will($this->returnValue(true));
  $this->assertSame($this->_current_page, $this->_retriever->get_page($this->_pages, $this->_current_page));
 }
}

/* End of file content_source_page_retriever.test.php */
/* Location: ./WalkerCMS/tests/content_source_page_retriever.test.php */