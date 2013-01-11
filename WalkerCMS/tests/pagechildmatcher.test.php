<?php
class PageChildMatcherTest extends PHPUnit_Framework_TestCase
{
 private $_page = null;
 private $_parent = null;
 private $_logger = null;
 private $_matcher = null;
 
 protected function setUp()
 {
  $this->_page = new PageModel(array('id' => 'faq', 'parent' => 'about', 'show_in_nav' => true));
  $this->_parent = new PageModel(array('id' => 'about', 'show_in_nav' => true));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_matcher = new PageChildMatcher($this->_logger);
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_matcher->is_match($this->_page, $this->_parent);
 }
 
 public function testPageIsChildOfParent()
 {
  $this->assertTrue($this->_matcher->is_match($this->_page, $this->_parent));
 }
 
 public function testPageIsNotChildOfParent()
 {
  $this->assertFalse($this->_matcher->is_match($this->_parent, $this->_parent));
 }
}

/* End of file pagechildmatcher.test.php */
/* Location: ./WalkerCMS/tests/pagechildmatcher.test.php */