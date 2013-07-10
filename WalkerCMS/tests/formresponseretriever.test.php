<?php
class FormResponseRetrieverTest extends PHPUnit_Framework_TestCase
{
 private $_request = null;
 private $_form_generator = null;
 private $_response = null;
 private $_redirect = null;
 private $_logger = null;
 private $_retriever = null;
 
 protected function setUp()
 {
  $this->_request = $this->getMock('IRequestAdapter', array('is_ajax', 'ip_address', 'user_agent'));
  $this->_response = $this->getMock('IResponseAdapter', array('send_json', 'error'));
  $this->_redirect = $this->getMock('IRedirectAdapter', array('to'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_retriever = new FormResponseRetriever(
    $this->_request,
    $this->_form_generator,
    $this->_response,
    $this->_redirect,
    $this->_logger);
 }
 
 public function testGetResponse_UseLogger()
 {
  $result = array('submitting_page_id' => 'home');
  $context = array();
  $this->_logger->expects($this->atLeastOnce())
       ->method('debug');
 
  $this->_retriever->get_response($result, $context);
 }
 
 public function testGetResponse()
 {
  $result = array('submitting_page_id' => 'home');
  $context = array();
  $expected = array('generated redirect');
  $this->_redirect->expects($this->once())
                  ->method('to')
                  ->with('home', $this->equalTo(array('form_data' => $result)))
                  ->will($this->returnValue($expected));
  
  $this->assertSame($expected, $this->_retriever->get_response($result, $context));
 }
}

/* End of file formresponseretriever.test.php */
/* Location: ./WalkerCMS/tests/formresponseretriever.test.php */