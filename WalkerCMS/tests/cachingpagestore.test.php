<?php
class CachingPageStoreTest extends PHPUnit_Framework_TestCase
{
 private $_cache = null;
 private $_inner_store = null;
 private $_logger = null;
 private $_store = null;
 private $_cache_key_prefix = null;
 
 private function generate_cache_key($additional_data)
 {
  return sha1($this->_cache_key_prefix . $additional_data);
 }
 
 protected function setUp()
 {
  $this->_cache = $this->getMock('ICache', array('has', 'get', 'put', 'remember', 'forget'));
  $this->_inner_store = $this->getMock('IPageStore', array('get_all_pages', 'get_page', 'exists', 'get_parent', 'get_children', 'get_all_by_properties'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_store = new CachingPageStore($this->_cache, $this->_inner_store, $this->_logger);
  
  $this->_cache_key_prefix = serialize($this->_inner_store);
 }
 
 public function testGetAllPages_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_store->get_all_pages();
 }
 
 public function testGetAllPages_NotInCache()
 {
  $expected_key = $this->generate_cache_key('.get_all_pages');
  $expected = array('some_pages');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_all_pages')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_all_pages();
  $this->assertSame($expected, $result);
 }
 
 public function testGetAllPages_InCache()
 {
  $expected_key = $this->generate_cache_key('.get_all_pages');
  $expected = array('some_pages');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_all_pages();
  $this->assertSame($expected, $result);
 }
 
 public function testGetPage_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_store->get_page('home');
 }
 
 public function testGetPage_NotInCache()
 {
  $expected_key = $this->generate_cache_key('.get_page(home)');
  $expected = array('home page');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_page')
                     ->with('home')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_page('home');
  $this->assertSame($expected, $result);
 }
 
 public function testGetPage_NotInCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.get_page(contact)');
  $expected = array('contact page');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_page')
                     ->with('contact')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_page('contact');
  $this->assertSame($expected, $result);
 }
 
 public function testGetPage_InCache()
 {
  $expected_key = $this->generate_cache_key('.get_page(contact)');
  $expected = array('contact page');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_page('contact');
  $this->assertSame($expected, $result);
 }
 
 public function testGetPage_InCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.get_page(home)');
  $expected = array('home page');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_page('home');
  $this->assertSame($expected, $result);
 }

 public function testExists_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_store->exists('home');
 }
 
 public function testExists_NotInCache()
 {
  $expected_key = $this->generate_cache_key('.exists(home)');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('exists')
                     ->with('home')
                     ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
                              ->method('put')
                              ->with($expected_key, true, 10080);
  $result = $this->_store->exists('home');
  $this->assertTrue($result);
 }
 
 public function testExists_NotInCache_DifferentExistence()
 {
  $expected_key = $this->generate_cache_key('.exists(contact)');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('exists')
                     ->with('contact')
                     ->will($this->returnValue(false));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, false, 10080);
  $result = $this->_store->exists('contact');
  $this->assertFalse($result);
 }
 
 public function testExists_InCache()
 {
  $expected_key = $this->generate_cache_key('.exists(contact)');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $result = $this->_store->exists('contact');
  $this->assertTrue($result);
 }
 
 public function testExists_InCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.exists(home)');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $result = $this->_store->exists('home');
  $this->assertFalse($result);
 }
 
 public function testGetParent_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_store->get_parent('home');
 }
 
 public function testGetParent_NotInCache()
 {
  $expected_key = $this->generate_cache_key('.get_parent(home)');
  $expected = array('home page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_parent')
                     ->with('home')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_parent('home');
  $this->assertSame($expected, $result);
 }
 
 public function testGetParent_NotInCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.get_parent(contact)');
  $expected = array('contact page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_parent')
                     ->with('contact')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_parent('contact');
  $this->assertSame($expected, $result);
 }
 
 public function testGetParent_InCache()
 {
  $expected_key = $this->generate_cache_key('.get_parent(contact)');
  $expected = array('contact page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_parent('contact');
  $this->assertSame($expected, $result);
 }
 
 public function testGetParent_InCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.get_parent(home)');
  $expected = array('home page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_parent('home');
  $this->assertSame($expected, $result);
 }
 
 public function testGetChildren_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_store->get_children('home');
 }
 
 public function testGetChildren_NotInCache()
 {
  $expected_key = $this->generate_cache_key('.get_children(home)');
  $expected = array('home page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_children')
                     ->with('home')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_children('home');
  $this->assertSame($expected, $result);
 }
 
 public function testGetChildren_NotInCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.get_children(contact)');
  $expected = array('contact page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_children')
                     ->with('contact')
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_children('contact');
  $this->assertSame($expected, $result);
 }
 
 public function testGetChildren_InCache()
 {
  $expected_key = $this->generate_cache_key('.get_children(contact)');
  $expected = array('contact page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_children('contact');
  $this->assertSame($expected, $result);
 }
 
 public function testGetChildren_InCache_DifferentID()
 {
  $expected_key = $this->generate_cache_key('.get_children(home)');
  $expected = array('home page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_children('home');
  $this->assertSame($expected, $result);
 }
 
 
 
 
 
 
 
 
 
 
 
 
 public function testGetAllByProperties_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_store->get_all_by_properties(array('id' => 'home'));
 }
 
 public function testGetAllByProperties_NotInCache()
 {
  $properties = array('id' => 'home');
  $expected_key = $this->generate_cache_key('.get_all_by_properties('.serialize($properties).')');
  $expected = array('home page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_all_by_properties')
                     ->with($this->equalTo($properties))
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_all_by_properties($properties);
  $this->assertSame($expected, $result);
 }
 
 public function testGetAllByProperties_NotInCache_DifferentProperties()
 {
  $properties = array('id' => 'contact', 'show_in_nav' => true);
  $expected_key = $this->generate_cache_key('.get_all_by_properties('.serialize($properties).')');
  $expected = array('contact page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(false));
  $this->_inner_store->expects($this->once())
                     ->method('get_all_by_properties')
                     ->with($properties)
                     ->will($this->returnValue($expected));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with($expected_key, $expected, 10080);
  $result = $this->_store->get_all_by_properties($properties);
  $this->assertSame($expected, $result);
 }
 
 public function testGetAllByProperties_InCache()
 {
  $properties = array('id' => 'contact');
  $expected_key = $this->generate_cache_key('.get_all_by_properties('.serialize($properties).')');
  $expected = array('contact page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_all_by_properties($properties);
  $this->assertSame($expected, $result);
 }
 
 public function testGetAllByProperties_InCache_DifferentID()
 {
  $properties = array('id' => 'home', 'show_in_nav' => true);
  $expected_key = $this->generate_cache_key('.get_all_by_properties('.serialize($properties).')');
  $expected = array('home page parent');
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($expected_key)
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($expected_key)
               ->will($this->returnValue($expected));
  $result = $this->_store->get_all_by_properties($properties);
  $this->assertSame($expected, $result);
 }
}
/* End of file cachingpagestore.test.php */
/* Location: ./WalkerCMS/tests/cachingpagestore.test.php */