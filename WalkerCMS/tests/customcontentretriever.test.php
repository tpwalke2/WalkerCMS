<?php
class CustomContentRetrieverTest extends PHPUnit_Framework_TestCase
{
 private $_data_generator = null;
 private $_view_adapter = null;
 private $_logger = null;
 private $_content_retriever = null;
 private $_context = null;
 private $_current_page = null;
 
 protected function setUp()
 {
  $this->_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_view_adapter = $this->getMock('IViewAdapter', array('generate_view'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_content_retriever = new CustomContentRetriever($this->_data_generator, $this->_view_adapter, $this->_logger);
  $this->_context = new AppContext();
  $this->_current_page = new PageModel(array('id' => 'home'));
 }
 
 public function testRetrieveContent_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $data = array();
  $this->_data_generator->expects($this->once())
                        ->method('generate_data')
                        ->with($this->equalTo($this->_current_page), $this->equalTo($this->_context))
                        ->will($this->returnValue($data));
  $this->_view_adapter->expects($this->once())
                      ->method('generate_view')
                      ->with('partials.page_inclusion', $this->equalTo($data))
                      ->will($this->returnValue('Generated page'));
  $result = $this->_content_retriever->retrieve_content($this->_current_page, $this->_context);
 }
 
 public function testRetrieveContent()
 {
  $data = array();
  $this->_data_generator->expects($this->once())
                        ->method('generate_data')
                        ->with($this->equalTo($this->_current_page), $this->equalTo($this->_context))
                        ->will($this->returnValue($data));
  $this->_view_adapter->expects($this->once())
                      ->method('generate_view')
                      ->with('partials.page_inclusion', $this->equalTo($data))
                      ->will($this->returnValue('Generated page'));
  $this->assertEquals('Generated page', $this->_content_retriever->retrieve_content($this->_current_page, $this->_context));
 }
}

/* End of file customcontentretriever.test.php */
/* Location: ./WalkerCMS/tests/customcontentretriever.test.php */