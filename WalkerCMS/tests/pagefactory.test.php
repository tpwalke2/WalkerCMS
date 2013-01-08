<?php
class PageFactoryTest extends PHPUnit_Framework_TestCase
{
 private $_factory = null;
 
 protected function setUp()
 {
  $this->_factory = new PageFactory();
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