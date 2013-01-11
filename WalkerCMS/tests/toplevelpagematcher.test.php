<?php
class TopLevelPageMatcherTest extends PHPUnit_Framework_TestCase
{
 private $_page = null;
 private $_matcher = null;
 private $_logger = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_page = new PageModel(array('id' => 'home', 'show_in_nav' => true));
  $this->_matcher = new TopLevelPageMatcher($this->_logger);
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_matcher->is_match($this->_page, null);
 }
 
 public function testPageIsTopLevel()
 {
  $this->assertTrue($this->_matcher->is_match($this->_page, null));
 }
 
 public function testPageIsNotTopLevel()
 {
  $this->_page = new PageModel(array(
    'id' => 'about',
    'show_in_nav' => true,
    'parent' => 'home',
  ));
  $this->assertFalse($this->_matcher->is_match($this->_page, null));
 }
 
 public function testPageNotVisibleInNav()
 {
  $this->_page = new PageModel(array(
    'id' => 'about',
    'show_in_nav' => false,
    'parent' => 'home',
  ));
  $this->assertFalse($this->_matcher->is_match($this->_page, null));
 }
}

/* End of file toplevelpagematcher.test.php */
/* Location: ./WalkerCMS/tests/toplevelpagematcher.test.php */