<?php
class AppContextTest extends PHPUnit_Framework_TestCase
{
 private $_context = null;
 private $_page_store = null;
 
 protected function setUp()
 {
  $this->_page_store = $this->getMock('IPageStore', array('get_all_pages', 'get_page', 'exists', 'get_parent', 'get_children', 'get_all_by_properties'));
  $this->_context = new AppContext();
 }
 
 public function testGetCurrentPage()
 {
  $current_page = array('id' => 'home');
  
  $this->_page_store->expects($this->once())
                    ->method('get_page')
                    ->with('home')
                    ->will($this->returnValue($current_page));
  $this->_context->set_page_store($this->_page_store);
  $this->_context->set_current_page_id('home');
  $this->assertSame($current_page, $this->_context->get_current_page());
 }
 
 public function testGetCurrentPage_StoreNotAvailable()
 {
  $this->_context->set_current_page_id('home');
  $this->assertNull($this->_context->get_current_page());
 }
 
 public function testGetCurrentPage_CurrentPageIDNotSet()
 {
   $this->_page_store->expects($this->never())
                    ->method('get_page');
  $this->_context->set_page_store($this->_page_store);
  $this->assertNull($this->_context->get_current_page());
 }
 
 public function testGetPages()
 {
  $pages = array('home' => array('id' => 'home'));
  
  $this->_page_store->expects($this->once())
                    ->method('get_all_pages')
                    ->will($this->returnValue($pages));
  $this->_context->set_page_store($this->_page_store);
  $this->assertSame($pages, $this->_context->get_pages());
 }
 
 public function testGetPages_StoreNotAvailable()
 {
  $this->assertNull($this->_context->get_pages());
 }
 
 public function testGetAndSetItem()
 {
  $pages = array('home' => array('id' => 'home'));
  $this->_context->set_items($pages);
  $this->assertEquals($pages, $this->_context->get_items());
 }
 
 public function testSetAndClearItem()
 {
  $this->_context->set_items(array('home' => array('id' => 'home')));
  $this->_context->clear_items();
  $this->assertNull($this->_context->get_items());
 }
 
 public function testGetPreviouslyUnsetItem()
 {
  $this->assertNull($this->_context->get_items());
 }
 
 public function testGetPreviouslyUnsetItemWithDefault()
 {
  $default = array('id' => 'home');
  $this->assertEquals($default, $this->_context->get_content_source_page($default));
 }
 
 /**
  * @expectedException BadMethodCallException
  */
 public function testGetIncorrectMethodSignature()
 {
  $this->_context->getpages();
 }
 
 /**
  * @expectedException BadMethodCallException
  */
 public function testSetIncorrectMethodSignature()
 {
  $this->_context->setpages(array('home' => array('id' => 'home')));
 }
 
 /**
  * @expectedException BadMethodCallException
  */
 public function testCompletelyOffTheMarkMethodSignature()
 {
  $this->_context->is_valid_page();
 }
}

/* End of file appcontext.test.php */
/* Location: ./WalkerCMS/tests/appcontext.test.php */