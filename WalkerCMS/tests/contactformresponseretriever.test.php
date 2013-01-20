<?php
class ContactFormResponseRetrieverTest extends PHPUnit_Framework_TestCase
{
 private $_request = null;
 private $_contact_form_generator = null;
 private $_response = null;
 private $_redirect = null;
 private $_logger = null;
 private $_retriever = null;
 private $_result = null;
 private $_context = null;
 private $_view = null;
 
 protected function setUp()
 {
  $this->_request = $this->getMock('IRequestAdapter', array('is_ajax', 'ip_address', 'user_agent'));
  $this->_contact_form_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_response = $this->getMock('IResponseAdapter', array('send_json', 'error'));
  $this->_redirect = $this->getMock('IRedirectAdapter', array('to'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_retriever = new ContactFormResponseRetriever(
    $this->_request,
    $this->_contact_form_generator,
    $this->_response,
    $this->_redirect,
    $this->_logger);
  $this->_result = array('submitting_page_id' => 'contact');
  $this->_context = new AppContext();
  $this->_view = $this->getMock('IViewWrapper', array('render'));
 }
 
 public function testGetResponse_NonAJAX()
 {
  $redirect_data = array('context' => $this->_context);
  $expected_response = array('responded');
  $this->_request->expects($this->once())
                 ->method('is_ajax')
                 ->will($this->returnValue(false));
  $this->_redirect->expects($this->once())
                  ->method('to')
                  ->with('contact', $this->equalTo($redirect_data))
                  ->will($this->returnValue($expected_response));
  $actual_response = $this->_retriever->get_response($this->_result, $this->_context);
 }
 
 public function testGetResponse_NonAJAX_InteractWithLogger()
 {
  $redirect_data = array('context' => $this->_context);
  $expected_response = array('responded');
  $this->_request->expects($this->once())
                 ->method('is_ajax')
                 ->will($this->returnValue(false));
  $this->_redirect->expects($this->once())
                  ->method('to')
                  ->with('contact', $this->equalTo($redirect_data))
                  ->will($this->returnValue($expected_response));
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $actual_response = $this->_retriever->get_response($this->_result, $this->_context);
 }
 
 public function testGetResponse_AJAX()
 {
  $page = new PageModel(array('id' => 'contact'));
  $this->_context->set_current_page($page);
  $generated_content = array('contact_form' => $this->_view);
  $jsonified_result = array('jsonified');
  $new_result = $this->_result;
  $new_result['content'] = 'Rendered content';
  $this->assertFalse(isset($this->_result['content']));
  $this->_request->expects($this->once())
                 ->method('is_ajax')
                 ->will($this->returnValue(true));
  $this->_contact_form_generator->expects($this->once())
                                ->method('generate_data')
                                ->with($this->equalTo($page), $this->equalTo($this->_context))
                                ->will($this->returnValue($generated_content));
  $this->_view->expects($this->once())
              ->method('render')
              ->will($this->returnValue('Rendered content'));
  $this->_response->expects($this->once())
                  ->method('send_json')
                  ->with($this->equalTo($new_result))
                  ->will($this->returnValue($jsonified_result));
  $this->assertEquals($jsonified_result, $this->_retriever->get_response($this->_result, $this->_context));
 }
 
 public function testGetResponse_AJAX_UseLogger()
 {
  $page = new PageModel(array('id' => 'contact'));
  $this->_context->set_current_page($page);
  $generated_content = array('contact_form' => $this->_view);
  $jsonified_result = array('jsonified');
  $new_result = $this->_result;
  $new_result['content'] = 'Rendered content';
  $this->_request->expects($this->once())
                 ->method('is_ajax')
                 ->will($this->returnValue(true));
  $this->_contact_form_generator->expects($this->once())
                                ->method('generate_data')
                                ->with($this->equalTo($page), $this->equalTo($this->_context))
                                ->will($this->returnValue($generated_content));
  $this->_view->expects($this->once())
              ->method('render')
              ->will($this->returnValue('Rendered content'));
  $this->_response->expects($this->once())
                  ->method('send_json')
                  ->with($this->equalTo($new_result))
                  ->will($this->returnValue($jsonified_result));
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_retriever->get_response($this->_result, $this->_context);
 }
}

/* End of file contactformresponseretriever.test.php */
/* Location: ./WalkerCMS/tests/contactformresponseretriever.test.php */