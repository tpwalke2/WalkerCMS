<?php
use Laravel\CLI\Tasks\Bundle\Publisher;

class ConfigPageStoreTest extends PHPUnit_Framework_TestCase
{
 private $_page_factory = null;
 private $_config = null;
 private $_logger = null;
 private $_store = null;
 private $_page_definitions = null;
 
 
 protected function setUp()
 {
  $this->_factory = $this->getMock('IPageFactory', array('create'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_store = new ConfigPageStore($this->_factory, 
                                      $this->_config,
                                      $this->_logger);
  $this->_page_definitions = array(
    'home' => array('id' => 'home', 'show_in_nav' => true),
    'about' => array('id' => 'about', 'show_in_nav' => true),
    'faq' => array('id' => 'faq', 'parent' => 'about', 'show_in_nav' => false, 'property1' => true),
    'careers' => array('id' => 'careers', 'parent' => 'about', 'show_in_nav' => true),
    'contact' => array('id' => 'contact', 'show_in_nav' => true)
    );
 }
 
 public function testGetAllPages_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'])));
  $pages = $this->_store->get_all_pages();
 }
 
 public function testGetAllPages_OnePage()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'])));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->with($this->equalTo($this->_page_definitions['home']))
                 ->will($this->returnValue(new PageModel($this->_page_definitions['home'])));
  
  $pages = $this->_store->get_all_pages();
  $this->assertEquals(1, count($pages));
  $this->assertEquals('home', $pages['home']->get_id());
 }
 
 public function testGetAllPages_NoPages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array()));
  $this->_factory->expects($this->never())
                 ->method('create');
  $pages = $this->_store->get_all_pages();
  $this->assertEquals(0, count($pages));
 }
 
 public function testGetAllPages_NullPages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(null));
  $this->_factory->expects($this->never())
                 ->method('create');
  $pages = $this->_store->get_all_pages();
  $this->assertEquals(0, count($pages));
 }
 
 public function testGetAllPages_MultiplePages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'],
                                                'about' => $this->_page_definitions['about'],
                                                'contact' => $this->_page_definitions['contact'])));
  $this->_factory->expects($this->exactly(3))
                 ->method('create')
                 ->with($this->logicalOr(
                   $this->equalTo($this->_page_definitions['home']),
                   $this->equalTo($this->_page_definitions['about']),
                   $this->equalTo($this->_page_definitions['contact'])))
                 ->will($this->returnCallback(array($this, 'createCallback')));
  
  $pages = $this->_store->get_all_pages();
  $this->assertEquals(3, count($pages));
  $this->assertEquals('home', $pages['home']->get_id());
  $this->assertEquals('about', $pages['about']->get_id());
  $this->assertEquals('contact', $pages['contact']->get_id());
 }
 
 public function testGetPage_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'])));
  $result = $this->_store->get_page('home');
 }
 
 public function testGetPage()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'],
                                                'about' => $this->_page_definitions['about'],
                                                'contact' => $this->_page_definitions['contact'])));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->with($this->equalTo($this->_page_definitions['home']))
                 ->will($this->returnValue(new PageModel($this->_page_definitions['home'])));
  
  $result = $this->_store->get_page('home');
  $this->assertEquals('home', $result->get_id());
 }
 
 public function testGetPage_PageNotFound()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('about' => $this->_page_definitions['about'],
                                                'contact' => $this->_page_definitions['contact'])));
  $this->_factory->expects($this->never())
                 ->method('create');
 
  $result = $this->_store->get_page('home');
  $this->assertNull($result);
 }
 
 public function testExists_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'])));
  $result = $this->_store->exists('home');
 }
 
 public function testExists()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'],
                                               'about' => $this->_page_definitions['about'],
                                               'contact' => $this->_page_definitions['contact'])));
  $this->assertTrue($this->_store->exists('home'));
 }
 
 public function testExists_PageNotFound()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('about' => $this->_page_definitions['about'],
                                                'contact' => $this->_page_definitions['contact'])));
  $this->assertFalse($this->_store->exists('home'));
 }
 
 public function testGetParent_UseLogger()
 {
  $this->_logger->expects($this->exactly(2))->method('debug');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'])));
  $result = $this->_store->get_parent('home');
 }
 
 public function testGetParent_CandidateChildNotFound()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(null));
  $result = $this->_store->get_parent('home');
  $this->assertNull($result);
 }
 
 public function testGetParent_ParentUnset()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->with($this->equalTo($this->_page_definitions['home']))
                 ->will($this->returnValue(new PageModel($this->_page_definitions['home'])));
  $result = $this->_store->get_parent('home');
  $this->assertNull($result);
 }
 
 public function testGetParent_ParentSetAndExists()
 {
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->any())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_parent('faq');
  $this->assertEquals('about', $result->get_id());
 }
 
 public function testGetParent_ParentSetAndDoesNotExist()
 {
  $this->_page_definitions['faq']['parent'] = 'nonexistent';
  
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->will($this->returnValue(new PageModel($this->_page_definitions['faq'])));
  $result = $this->_store->get_parent('faq');
  $this->assertNull($result);
 }
 
 public function testGetChildren_UseLogger()
 {
  $this->_logger->expects($this->exactly(2))->method('debug');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array('home' => $this->_page_definitions['home'])));
  $this->_factory->expects($this->any())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_children('home');
 }
 
 public function testGetChildren()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->any())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_children('about');
  $this->assertEquals(2, count($result));
  $this->assertEquals('faq', $result[0]->get_id());
  $this->assertEquals('careers', $result[1]->get_id());
 }
 
 public function testGetChildren_NoChildren()
 {
  $this->_config->expects($this->once())
  ->method('get')
  ->with('walkercms.pages')
  ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->any())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_children('home');
  $this->assertEquals(0, count($result));
 }
 
 public function testGetChildren_NoPages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array()));
  $result = $this->_store->get_children('home');
  $this->assertEquals(0, count($result));
 }
 
 public function testGetChildren_NullPages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(null));
  $result = $this->_store->get_children('home');
  $this->assertEquals(0, count($result));
 }
 
 public function testGetAllByProperties_ByIDOnly()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_all_by_properties(array('id' => 'home'));
  $this->assertEquals(1, count($result));
  $this->assertEquals('home', $result[0]->get_id());
 }
 
 public function testGetAllByProperties_DifferentID()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_all_by_properties(array('id' => 'about'));
  $this->assertEquals(1, count($result));
  $this->assertEquals('about', $result[0]->get_id());
 }
 
 public function testGetAllByProperties_TwoProperties()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_all_by_properties(array('parent' => 'about', 'show_in_nav' => true));
  $this->assertEquals(1, count($result));
  $this->assertEquals('careers', $result[0]->get_id());
 }
 
 public function testGetAllByProperties_TwoPropertiesOneUnset()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_all_by_properties(array('parent' => 'about', 'property1' => true));
  $this->assertEquals(1, count($result));
  $this->assertEquals('faq', $result[0]->get_id());
 }
 
 public function testGetAllByProperties_PropertyValueNotFound()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->never())
                 ->method('create');
  $result = $this->_store->get_all_by_properties(array('id' => 'store'));
  $this->assertEquals(0, count($result));
 }
 
 public function testGetAllByProperties_PropertyValueNotSet()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->never())
                 ->method('create');
  $result = $this->_store->get_all_by_properties(array('unsetProperty' => '42'));
  $this->assertEquals(0, count($result));
 }
 
 public function testGetAllByProperties_NoPages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array()));
  $this->_factory->expects($this->never())
                 ->method('create');
  $result = $this->_store->get_all_by_properties(array('id' => 'home'));
  $this->assertEquals(0, count($result));
 }

 public function testGetAllByProperties_NullPages()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(null));
  $this->_factory->expects($this->never())
                 ->method('create');
  $result = $this->_store->get_all_by_properties(array('id' => 'home'));
  $this->assertEquals(0, count($result));
 }
 
 public function testGetAllByProperties_NoProperties()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->exactly(5))
                 ->method('create')
                 ->will($this->returnCallback(array($this, 'createCallback')));
  $result = $this->_store->get_all_by_properties(array());
  $this->assertEquals(5, count($result));
 }
 
 public function testGetAllByProperties_NullProperties()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue($this->_page_definitions));
  $this->_factory->expects($this->never())
                 ->method('create');
  $result = $this->_store->get_all_by_properties(null);
  $this->assertEquals(0, count($result));
 }
 
 public function testGetAllByProperties_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.pages')
                ->will($this->returnValue(array()));
  $pages = $this->_store->get_all_by_properties(array('id' => 'home'));
 }
 
 public function createCallback($definition)
 {
  return new PageModel($definition);
 }
}

/* End of file configpagestore.test.php */
/* Location: ./WalkerCMS/tests/configpagestore.test.php */