<?php
class PageFactoryTest extends PHPUnit_Framework_TestCase
{
 private $_logger = null;
 private $_factory = null;
 
 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_factory = new PageFactory($this->_logger);
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_factory->create(array('id' => 'home'));
 }
 
 public function testNotNull()
 {
  $this->assertNotNull($this->_factory->create(array('id' => 'home')));
 }
 
 public function testCorrectType()
 {
  $this->assertInstanceOf('PageModel', $this->_factory->create(array('id' => 'home')));
 }
 
 public function testDifferentInstancesOnSubsequentCalls()
 {
  $page1 = $this->_factory->create(array('id' => 'home'));
  $page2 = $this->_factory->create(array('id' => 'home'));
  $this->assertNotSame($page1, $page2);
 }
}

/* End of file pagefactory.test.php */
/* Location: ./WalkerCMS/tests/pagefactory.test.php */