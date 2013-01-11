<?php
class ContextFactoryTest extends PHPUnit_Framework_TestCase
{
 private $_pages_retriever = null;
 private $_page_id_validator = null;
 private $_session = null;
 private $_logger = null;
 private $_factory = null;
 private $_existing_context = null;
 private $_pages = null;
 
 protected function setUp()
 {
  $this->_pages_retriever = $this->getMock('IPagesRetriever', array('get_pages'));
  $this->_page_id_validator = $this->getMock('IPageIDValidator', array('get_validated_page_id'));
  $this->_session = $this->getMock('ISessionAdapter', array('get', 'forget'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_factory = new ContextFactory(
    $this->_pages_retriever,
    $this->_page_id_validator,
    $this->_session,
    $this->_logger
    );
  
  $this->_existing_context = new AppContext();
  $this->_pages = array('home' => array());
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
 
  $result = $this->_factory->create('home');
  $this->assertSame($this->_pages, $result->get_pages());
 }
 
 public function testCreate_SetCurrentPage()
 {
  $this->_pages['home'] = new PageModel(array('id' => 'home'));
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
 
  $result = $this->_factory->create('home');
  $this->assertSame($this->_pages['home'], $result->get_current_page());
 }
}

/* End of file contextfactory.test.php */
/* Location: ./WalkerCMS/tests/contextfactory.test.php */