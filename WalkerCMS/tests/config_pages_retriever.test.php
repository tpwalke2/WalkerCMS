<?php
require_once(path('app') . 'helpers/config_pages_retriever.php');
require_once(path('app') . 'helpers/page_factory.php');
require_once(path('app') . 'helpers/interfaces/config_adapter.php');
require_once(path('app') . 'models/page_model.php');

class TestConfigPagesRetriever extends PHPUnit_Framework_TestCase
{
 private $_factory = null;
 private $_retriever = null;
 private $_config_adapter = null;
 
 protected function setUp()
 {
  $this->_factory = $this->getMock('PageFactory', array('create'));
  $this->_config_adapter = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_retriever = new ConfigPagesRetriever($this->_factory, $this->_config_adapter);
 }
 
 public function testGetOnePage()
 {
  $home_page_definition = array('id' => 'home');
  
  $this->_config_adapter->expects($this->once())
                        ->method('get')
                        ->with('walkercms.pages')
                        ->will($this->returnValue(array('home' => $home_page_definition)));
  $this->_factory->expects($this->once())
                 ->method('create')
                 ->with($this->equalTo($home_page_definition))
                 ->will($this->returnValue(new PageModel($home_page_definition)));
  
  $pages = $this->_retriever->get_pages();
  $this->assertEquals(1, count($pages));
  $this->assertEquals('home', $pages['home']->get_id());
 }
 
 public function testGetMultiplePages()
 {
  $home_page_definition = array('id' => 'home');
  $about_page_definition = array('id' => 'about');
  $contact_page_definition = array('id' => 'contact');

  $this->_config_adapter->expects($this->once())
                        ->method('get')
                        ->with('walkercms.pages')
                        ->will($this->returnValue(array('home' => $home_page_definition,
                                                        'about' => $about_page_definition,
                                                        'contact' => $contact_page_definition)));
  $this->_factory->expects($this->exactly(3))
                 ->method('create')
                 ->with($this->logicalOr(
                   $this->equalTo($home_page_definition),
                   $this->equalTo($about_page_definition),
                   $this->equalTo($contact_page_definition)))
                 ->will($this->returnCallback(array($this, 'createCallback')));
  
  $pages = $this->_retriever->get_pages();
  $this->assertEquals(3, count($pages));
  $this->assertEquals('home', $pages['home']->get_id());
  $this->assertEquals('about', $pages['about']->get_id());
  $this->assertEquals('contact', $pages['contact']->get_id());
 }
 
 public function createCallback($definition)
 {
  return new PageModel($definition);
 } 
}

/* End of file config_pages_retriever.test.php */
/* Location: ./WalkerCMS/tests/config_pages_retriever.test.php */