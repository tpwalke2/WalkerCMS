<?php
class ContextFactoryTest extends PHPUnit_Framework_TestCase
{
 private $_pages_retriever = null;
 private $_page_id_validator = null;
 private $_content_source_page_retriever = null;
 private $_session = null;
 private $_logger = null;
 private $_factory = null;
 private $_existing_context = null;
 private $_pages = null;
 
 protected function setUp()
 {
  $this->_pages_retriever = $this->getMock('IPagesRetriever', array('get_pages'));
  $this->_page_id_validator = $this->getMock('IPageIDValidator', array('get_validated_page_id'));
  $this->_content_source_page_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_session = $this->getMock('ISessionAdapter', array('get', 'forget'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_factory = new ContextFactory(
    $this->_pages_retriever,
    $this->_page_id_validator,
    $this->_content_source_page_retriever,
    $this->_session,
    $this->_logger
    );
  
  $this->_existing_context = new AppContext();
  $this->_pages = array('home' => new PageModel(array('id' => 'home')));
 }
 
 public function testUseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())
                ->method('debug');

  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue($this->_existing_context));
  $this->_session->expects($this->once())
                 ->method('forget')
                 ->with('context')
                 ->will($this->returnValue(true));
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
  
  $result = $this->_factory->create('home');
  $this->assertSame($this->_existing_context, $result);
 }
 
 public function testCreate_ContextAlreadyOnSession()
 {
  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue($this->_existing_context));
  $this->_session->expects($this->once())
                 ->method('forget')
                 ->with('context')
                 ->will($this->returnValue(true));
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
  
  $result = $this->_factory->create('home');
  $this->assertSame($this->_existing_context, $result);
 }
 
 public function testCreate_GenerateNewContext()
 {
  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue(null));
  $this->_session->expects($this->never())
                 ->method('forget');
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
  
  $result = $this->_factory->create('home');
  $this->assertInstanceOf('AppContext', $result);
 }
 
 public function testCreate_GenerateNewContext_DifferentInstancesInSubsequentCalls()
 {
  $this->_session->expects($this->any())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue(null));
  $this->_pages_retriever->expects($this->any())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->any())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->any())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
 
  $result1 = $this->_factory->create('home');
  $result2 = $this->_factory->create('home');
  $this->assertNotSame($result1, $result2);
 }
 
 public function testCreate_SetPages()
 {
  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue(null));
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
 
  $result = $this->_factory->create('home');
  $this->assertSame($this->_pages, $result->get_pages());
 }
 
 public function testCreate_SetCurrentPage()
 {
  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue(null));
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
 
  $result = $this->_factory->create('home');
  $this->assertSame($this->_pages['home'], $result->get_current_page());
 }
 
 public function testCreate_SetContentSourcePage()
 {
  $this->_pages['home_content'] = new PageModel(array('id' => 'home_content'));
  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue(null));
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home_content']));
  
  $result = $this->_factory->create('home');
  $this->assertSame($this->_pages['home_content'], $result->get_content_source_page());
 }
 
 public function testCreate_SetSiteModel()
 {
  $this->_session->expects($this->once())
                 ->method('get')
                 ->with('context')
                 ->will($this->returnValue(null));
  $this->_pages_retriever->expects($this->once())
                         ->method('get_pages')
                         ->will($this->returnValue($this->_pages));
  $this->_page_id_validator->expects($this->once())
                           ->method('get_validated_page_id')
                           ->with($this->equalTo($this->_pages), 'home')
                           ->will($this->returnValue('home'));
  $this->_content_source_page_retriever->expects($this->once())
                                       ->method('get_page')
                                       ->with($this->_pages, $this->_pages['home'])
                                       ->will($this->returnValue($this->_pages['home']));
 
  $result = $this->_factory->create('home');
  $this->assertInstanceOf('SiteModel', $result->get_site());
 }
}

/* End of file contextfactory.test.php */
/* Location: ./WalkerCMS/tests/contextfactory.test.php */