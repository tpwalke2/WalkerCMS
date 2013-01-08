<?php
class AppContextTest extends PHPUnit_Framework_TestCase
{
 private $_context = null;
 
 protected function setUp()
 {
  $this->_context = new AppContext();
 }
 
 public function testGetAndSetItem()
 {
  $pages = array('home' => array('id' => 'home'));
  $this->_context->set_pages($pages);
  $this->assertEquals($pages, $this->_context->get_pages());
 }
 
 public function testGetPreviouslyUnsetItem()
 {
  $this->assertNull($this->_context->get_pages());
 }
 
 public function testGetPreviouslyUnsetItemWithDefault()
 {
  $default = array('id' => 'home');
  $this->assertEquals($default, $this->_context->get_current_page($default));
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