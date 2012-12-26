<?php
require_once(path('app') . 'helpers/top_level_page_matcher.php');
require_once(path('app') . 'models/page_model.php');

class TestTopLevelPageMatcher extends PHPUnit_Framework_TestCase
{
 private $_page = null;
 private $_matcher = null;
 
 protected function setUp()
 {
  $this->_page = new PageModel(array('id' => 'home', 'show_in_nav' => true));
  $this->_matcher = new TopLevelPageMatcher();
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

/* End of file top_level_page_matcher.test.php */
/* Location: ./WalkerCMS/tests/top_level_page_matcher.test.php */