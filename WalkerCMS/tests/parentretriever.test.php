<?php
class ParentRetrieverTest extends PHPUnit_Framework_TestCase
{
 private $_retriever = null;
 private $_pages = array();
 
 protected function setUp()
 {
  $this->_pages['home'] = new PageModel(array('id' => 'home', 'parent' => ''));
  $this->_retriever = new ParentRetriever();
 }
 
 public function testPageHasNoParent()
 {
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['home']);
  $this->assertNull($result);
 }
 
 public function testPageHasParent()
 {
  $this->_pages['about'] = new PageModel(array('id' => 'about', 'parent' => 'home'));
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['about']);
  $this->assertEquals($this->_pages['home'], $result);
 }
 
 public function testReferencedParentNotFound()
 {
  $this->_pages['about'] = new PageModel(array('id' => 'about', 'parent' => 'notfound'));
  $result = $this->_retriever->get_page($this->_pages, $this->_pages['about']);
  $this->assertNull($result);
 }
}

/* End of file parentretriever.test.php */
/* Location: ./WalkerCMS/tests/parentretriever.test.php */