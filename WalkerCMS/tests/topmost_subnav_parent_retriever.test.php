<?php
require_once(path('app') . 'helpers/topmost_subnav_parent_retriever.php');
require_once(path('app') . 'models/page_model.php');

class TestTopMostSubNavParentRetriever extends PHPUnit_Framework_TestCase
{
 private $_retriever = null;
 private $_pages = array();
 
 protected function setUp()
 {
  $this->_pages['home'] = new PageModel(array('id' => 'home', 'parent' => ''));
  $this->_retriever = new TopMostSubNavParentRetriever();
 }
 
 public function testPageHasNoParent()
 {
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['home']);
  $this->assertEquals($this->_pages['home'], $result);
 }
 
 public function testPageHasParent()
 {
  $this->_pages['about'] = new PageModel(array('id' => 'about', 'parent' => 'home'));
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['about']);
  $this->assertEquals($this->_pages['home'], $result);
 }
 
 public function testPageHasParentButAlsoSubNav()
 {
  $this->_pages['about'] = new PageModel(array('id' => 'about', 'parent' => 'home', 'sub_nav_on_page' => true));
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['about']);
  $this->assertEquals($this->_pages['about'], $result);
 }
 
 public function testDeepNesting()
 {
  $this->_pages['about'] = new PageModel(array('id' => 'about', 'parent' => 'home'));
  $this->_pages['faq'] = new PageModel(array('id' => 'faq', 'parent' => 'about'));
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['faq']);
  $this->assertEquals($this->_pages['home'], $result);
 }
 
 public function testDeepNestingWithSubNav()
 {
  $this->_pages['about'] = new PageModel(array('id' => 'about', 'parent' => 'home', 'sub_nav_on_page' => true));
  $this->_pages['faq'] = new PageModel(array('id' => 'faq', 'parent' => 'about'));
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['faq']);
  $this->assertEquals($this->_pages['about'], $result);
 }
}

/* End of file topmost_subnav_parent_retriever.test.php */
/* Location: ./WalkerCMS/tests/topmost_subnav_parent_retriever.test.php */