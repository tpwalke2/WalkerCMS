<?php
class SubNavRequiredDeterminerTest extends PHPUnit_Framework_TestCase
{
 private $_pages = null;
 private $_page = null;
 private $_parent_retriever = null;
 private $_logger = null;
 private $_determiner = null;
 
 protected function setUp()
 {
  $this->_parent_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_determiner = new SubNavRequiredDeterminer($this->_parent_retriever, $this->_logger);
  $this->_pages = array();
  $this->_page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'home', 'sub_nav_on_page' => false)));
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_page->expects($this->any())
              ->method('has_custom_sub_nav')
              ->will($this->returnValue(false));
  $this->_determiner->is_required($this->_pages, $this->_page);
 }
 
 public function testIsSubNavRequired_NoParent_NoCustomSubNav_HasSubNav()
 {
  $this->_parent_retriever->expects($this->any())
                          ->method('get_page')
                          ->will($this->returnValue(null));
  $this->_page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'home', 'sub_nav_on_page' => true)));
  $this->_page->expects($this->any())
              ->method('has_custom_sub_nav')
              ->will($this->returnValue(false));
  $this->assertTrue($this->_determiner->is_required($this->_pages, $this->_page));
 }
 
 public function testIsSubNavRequired_NoParent_NoCustomSubNav_NoSubNavOnPage()
 {
  $this->_parent_retriever->expects($this->any())
                          ->method('get_page')
                          ->will($this->returnValue(null));
  $this->_page->expects($this->any())
              ->method('has_custom_sub_nav')
              ->will($this->returnValue(false));
  $this->assertFalse($this->_determiner->is_required($this->_pages, $this->_page));
 }
 
 public function testIsSubNavRequired_NoParent_HasCustomSubNav()
 {
  $this->_parent_retriever->expects($this->any())
                          ->method('get_page')
                          ->will($this->returnValue(null));
  $this->_page->expects($this->any())
              ->method('has_custom_sub_nav')
              ->will($this->returnValue(true));
  $this->assertTrue($this->_determiner->is_required($this->_pages, $this->_page));
 }
 
 public function testIsSubNavRequired_ParentHasSubNav()
 {
  $this->_parent_retriever->expects($this->any())
                          ->method('get_page')
                          ->will($this->returnCallback(array($this, 'get_parent_callback')));
  $this->_page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'home', 'sub_nav_on_page' => true)));
  $this->_page->expects($this->any())
              ->method('has_custom_sub_nav')
              ->will($this->returnValue(false));
  $page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'faq', 'parent' => 'home')));

  $this->_pages['home'] = $this->_page;
  $this->_pages['faq'] = $page;
  $this->assertTrue($this->_determiner->is_required($this->_pages, $page));
 }
 
 public function testIsSubNavRequired_ParentDoesNotHaveSubNav()
 {
  $this->_parent_retriever->expects($this->any())
                          ->method('get_page')
                          ->will($this->returnCallback(array($this, 'get_parent_callback')));
  $this->_page->expects($this->any())
              ->method('has_custom_sub_nav')
              ->will($this->returnValue(false));
  $page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'faq', 'parent' => 'home')));
 
  $this->_pages['home'] = $this->_page;
  $this->_pages['faq'] = $page;
  $this->assertFalse($this->_determiner->is_required($this->_pages, $page));
 }
 
 /**
  * @expectedException InvalidArgumentException
  */
 public function testIsSubNavRequired_ParentNotFound()
 {
  $this->_parent_retriever->expects($this->any())
                          ->method('get_page')
                          ->will($this->returnValue(null));
  $this->_page->expects($this->any())
               ->method('has_custom_sub_nav')
               ->will($this->returnValue(true));
  
  $this->_page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'home', 'parent' => 'notfound')));
  $this->_pages['home'] = $this->_page;
  $this->_determiner->is_required($this->_pages, $this->_page);
 }
 
 /**
  * @expectedException InvalidArgumentException
  */
 public function testIsSubNavRequired_PageIsNull()
 {
  $this->_determiner->is_required($this->_pages, null);
 }
 
 public function get_parent_callback($pages, $page)
 {
  if (!isset($pages[$page->get_parent()])) { return null; }
  return $pages[$page->get_parent()];
 }
}

/* End of file subnavrequireddeterminer.test.php */
/* Location: ./WalkerCMS/tests/subnavrequireddeterminer.test.php */