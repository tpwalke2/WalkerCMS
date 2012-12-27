<?php
require_once(path('app') . 'helpers/page_child_matcher.php');
require_once(path('app') . 'models/page_model.php');

class TestPageChildMatcher extends PHPUnit_Framework_TestCase
{
 private $_page = null;
 private $_parent = null;
 private $_matcher = null;
 
 protected function setUp()
 {
  $this->_page = new PageModel(array('id' => 'faq', 'parent' => 'about', 'show_in_nav' => true));
  $this->_parent = new PageModel(array('id' => 'about', 'show_in_nav' => true));
  $this->_matcher = new PageChildMatcher();
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

/* End of file page_child_matcher.test.php */
/* Location: ./WalkerCMS/tests/page_child_matcher.test.php */