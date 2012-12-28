<?php
require_once(path('app') . 'helpers/interfaces/page_retriever.php');
require_once(path('app') . 'helpers/interfaces/page_matcher.php');
require_once(path('app') . 'helpers/first_child_page_retriever.php');
require_once(path('app') . 'helpers/interfaces/logger_adapter.php');
require_once(path('app') . 'models/page_model.php');

class TestFirstChildPageRetriever extends PHPUnit_Framework_TestCase
{
 private $_matcher = null;
 private $_parent_retriever = null;
 private $_retriever = null;
 private $_logger = null;
 private $_pages = null;
 private $_current_page = null;
 private $_parent_page = null;
 
 protected function setUp()
 {
  $this->_matcher = $this->getMock('IPageMatcher', array('is_match'));
  $this->_parent_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_retriever = new FirstChildPageRetriever($this->_matcher, $this->_parent_retriever, $this->_logger);
  $this->_current_page = new PageModel(array('id' => 'about', 'parent' => 'home'));
  $this->_parent_page = new PageModel(array('id' => 'home'));
  $this->_pages = array('home' => $this->_parent_page,
                        'help' => new PageModel(array('id' => 'help', 'parent' => 'home')),
                        'about' => $this->_current_page);
 }
 
 public function testGetPage_FullExample()
 {
  $this->_parent_retriever->expects($this->once())
                          ->method('get_page')
                          ->with($this->equalTo($this->_pages), $this->equalTo($this->_current_page))
                          ->will($this->returnValue($this->_parent_page));
  $this->_matcher->expects($this->exactly(2))
                 ->method('is_match')
                 ->with($this->logicalOr($this->equalTo($this->_pages['home']),
                                         $this->equalTo($this->_pages['help'])),
                        $this->equalTo($this->_parent_page))
                 ->will($this->onConsecutiveCalls(false, true));
  $this->assertSame($this->_pages['help'], $this->_retriever->get_page($this->_pages, $this->_current_page));
 }
 
 public function testGetPage_FirstChildNotFound()
 {
  $this->_parent_retriever->expects($this->once())
                          ->method('get_page')
                          ->with($this->equalTo($this->_pages), $this->equalTo($this->_current_page))
                          ->will($this->returnValue($this->_parent_page));
  $this->_matcher->expects($this->exactly(3))
                 ->method('is_match')
                 ->with($this->logicalOr($this->equalTo($this->_pages['home']),
                                         $this->equalTo($this->_pages['help']),
                                         $this->equalTo($this->_pages['about'])),
                        $this->equalTo($this->_parent_page))
                 ->will($this->onConsecutiveCalls(false, false, false));
  $this->assertNull($this->_retriever->get_page($this->_pages, $this->_current_page));
 }
 
 public function testGetPage_NoPages()
 {
  $this->_pages = array();
  $this->assertNull($this->_retriever->get_page($this->_pages, $this->_current_page));
 }
}

/* End of file first_child_page_retriever.test.php */
/* Location: ./WalkerCMS/tests/first_child_page_retriever.test.php */