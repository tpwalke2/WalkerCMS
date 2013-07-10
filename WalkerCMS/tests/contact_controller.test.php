<?php
class ContactControllerTest extends PHPUnit_Framework_TestCase
{
 private $_result_factory = null;
 private $_context_factory = null;
 private $_validation_retriever = null;
 private $_invalid_submission_processor = null;
 private $_spam_submission_processor = null;
 private $_valid_submission_processor = null;
 private $_response_retriever = null;
 private $_response = null;
 private $_logger = null;
 private $_controller = null;
 private $_context = null;
 private $_initial_result = null;
 private $_processed_result = null;
 private $_validation = null;

 protected function setUp()
 {
  $this->_context = new AppContext();
  $this->_initial_result = array('submitting_page_id' => 'contact', 'spam_control' => '');
  $this->_processed_result = $this->_initial_result;
  $this->_validation = $this->getMock('IValidationWrapper', array('fails', 'has_errors', 'get_errors', 'get_all_errors'));
  
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_result_factory = $this->getMock('IDataFactory', array('create'));
  $this->_context_factory = $this->getMock('IContextFactory', array('create'));
  $this->_validation_retriever = $this->getMock('IValidatorRetriever', array('get_form_validator'));
  $this->_invalid_submission_processor = $this->getMock('IDataProcessor', array('process'));
  $this->_spam_submission_processor = $this->getMock('IDataProcessor', array('process'));
  $this->_valid_submission_processor = $this->getMock('IDataProcessor', array('process'));
  $this->_response_retriever = $this->getMock('IResponseRetriever', array('get_response'));
  $this->_response = $this->getMock('IResponseAdapter', array('send_json', 'error'));
  $this->_controller = new Contact_Controller(
    $this->_result_factory,
    $this->_context_factory,
    $this->_validation_retriever,
    $this->_invalid_submission_processor,
    $this->_spam_submission_processor,
    $this->_valid_submission_processor,
    $this->_response_retriever,
    $this->_response,
    $this->_logger
    );
  
  $this->_validation_retriever->expects($this->any())
                              ->method('get_form_validator')
                              ->will($this->returnValue($this->_validation));
  }
 
 public function process_callback($result, $context)
 {
  $this->assertSame($this->_initial_result, $result);
  $this->assertEquals($this->_context, $context);
  return $this->_processed_result;
 }
 
 public function testPostContactSubmission_InvalidSubmission()
 {
  $this->_processed_result['processed'] = 'invalid';
  
  $this->_result_factory->expects($this->once())
                        ->method('create')
                        ->will($this->returnValue($this->_initial_result));
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('contact')
                         ->will($this->returnValue($this->_context));
  $this->_validation->expects($this->once())
                    ->method('fails')
                    ->will($this->returnValue(true));
  $this->_spam_submission_processor->expects($this->never())->method('process');
  $this->_valid_submission_processor->expects($this->never())->method('process');
  $this->_invalid_submission_processor->expects($this->once())
                                      ->method('process')
                                      ->will($this->returnCallback(array($this, 'process_callback')));
  $this->_response_retriever->expects($this->once())
                            ->method('get_response')
                            ->with($this->equalTo($this->_processed_result), $this->equalTo($this->_context))
                            ->will($this->returnValue(array('response')));
  $this->assertEquals(array('response'), $this->_controller->post_contact_submission());
  $this->assertEquals($this->_processed_result, $this->_context->get_contact_form_data());
 }
 
 public function testPostContactSubmission_InvalidSubmission_DifferentPage()
 {
  $this->_initial_result['submitting_page_id'] = 'other';
  $this->_processed_result['submitting_page_id'] = 'other';
  $this->_processed_result['processed'] = 'invalid';
  
  $this->_result_factory->expects($this->once())
                        ->method('create')
                        ->will($this->returnValue($this->_initial_result));
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('other')
                         ->will($this->returnValue($this->_context));
  $this->_validation->expects($this->once())
                    ->method('fails')
                    ->will($this->returnValue(true));
  $this->_spam_submission_processor->expects($this->never())->method('process');
  $this->_valid_submission_processor->expects($this->never())->method('process');
  $this->_invalid_submission_processor->expects($this->once())
                                      ->method('process')
                                      ->will($this->returnCallback(array($this, 'process_callback')));
  $this->_response_retriever->expects($this->once())
                            ->method('get_response')
                            ->with($this->equalTo($this->_processed_result), $this->equalTo($this->_context))
                            ->will($this->returnValue(array('response')));
  $this->assertEquals(array('response'), $this->_controller->post_contact_submission());
  $this->assertEquals($this->_processed_result, $this->_context->get_contact_form_data());
 }

 public function testPostContactSubmission_SpamSubmission()
 {
  $this->_initial_result['spam_control'] = 'This was filled in';
  $this->_processed_result['spam_control'] = 'This was filled in';
  $this->_processed_result['processed'] = 'spam';
  
  $this->_result_factory->expects($this->once())
                        ->method('create')
                        ->will($this->returnValue($this->_initial_result));
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('contact')
                         ->will($this->returnValue($this->_context));
  $this->_validation->expects($this->once())
                    ->method('fails')
                    ->will($this->returnValue(false));
  $this->_invalid_submission_processor->expects($this->never())->method('process');
  $this->_valid_submission_processor->expects($this->never())->method('process');
  $this->_spam_submission_processor->expects($this->once())
                                   ->method('process')
                                   ->will($this->returnCallback(array($this, 'process_callback')));
  $this->_response_retriever->expects($this->once())
                            ->method('get_response')
                            ->with($this->equalTo($this->_processed_result), $this->equalTo($this->_context))
                            ->will($this->returnValue(array('response')));
  $this->assertEquals(array('response'), $this->_controller->post_contact_submission());
  $this->assertEquals($this->_processed_result, $this->_context->get_contact_form_data());
 }
 
 public function testPostContactSubmission_ValidSubmission()
 {
  $this->_processed_result['processed'] = 'valid';
  
  $this->_result_factory->expects($this->once())
                        ->method('create')
                        ->will($this->returnValue($this->_initial_result));
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('contact')
                         ->will($this->returnValue($this->_context));
  $this->_validation->expects($this->once())
                    ->method('fails')
                    ->will($this->returnValue(false));
  $this->_spam_submission_processor->expects($this->never())->method('process');
  $this->_invalid_submission_processor->expects($this->never())->method('process');
  $this->_valid_submission_processor->expects($this->once())
                                    ->method('process')
                                    ->will($this->returnCallback(array($this, 'process_callback')));
  $this->_response_retriever->expects($this->once())
                            ->method('get_response')
                            ->with($this->equalTo($this->_processed_result), $this->equalTo($this->_context))
                            ->will($this->returnValue(array('response')));
  $this->assertEquals(array('response'), $this->_controller->post_contact_submission());
  $this->assertEquals($this->_processed_result, $this->_context->get_contact_form_data());
 }
 
 public function testPostContactSubmission_ClearFormValidation()
 {
  $this->_processed_result['processed'] = 'valid';
 
  $this->_result_factory->expects($this->once())
                        ->method('create')
                        ->will($this->returnValue($this->_initial_result));
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('contact')
                         ->will($this->returnValue($this->_context));
  $this->_validation->expects($this->once())
                    ->method('fails')
                    ->will($this->returnValue(false));
  $this->_spam_submission_processor->expects($this->never())->method('process');
  $this->_invalid_submission_processor->expects($this->never())->method('process');
  $this->_valid_submission_processor->expects($this->once())
                                    ->method('process')
                                    ->will($this->returnCallback(array($this, 'process_callback')));
  $this->_response_retriever->expects($this->once())
                            ->method('get_response')
                            ->with($this->equalTo($this->_processed_result), $this->equalTo($this->_context))
                            ->will($this->returnValue(array('response')));
  $this->assertEquals(array('response'), $this->_controller->post_contact_submission());
  $this->assertEquals('default returned', $this->_context->get_contact_validation('default returned'));
 }

 public function testPostContactSubmission_UseLogger()
 {
  $this->_processed_result['processed'] = 'valid';
  
  $this->_result_factory->expects($this->once())
                        ->method('create')
                        ->will($this->returnValue($this->_initial_result));
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('contact')
                         ->will($this->returnValue($this->_context));
  $this->_validation->expects($this->once())
                    ->method('fails')
                    ->will($this->returnValue(false));
  $this->_spam_submission_processor->expects($this->never())->method('process');
  $this->_invalid_submission_processor->expects($this->never())->method('process');
  $this->_valid_submission_processor->expects($this->once())
                                    ->method('process')
                                    ->will($this->returnCallback(array($this, 'process_callback')));
  $this->_response_retriever->expects($this->once())
                            ->method('get_response')
                            ->with($this->equalTo($this->_processed_result), $this->equalTo($this->_context))
                            ->will($this->returnValue(array('response')));
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_controller->post_contact_submission();
 }
 
 public function testInvalidMethod()
 {
  $this->_logger->expects($this->atLeastOnce())->method('error');
  $error_response = array('error');
  $method_info = array('method' => 'invalid', 'parameters' => array('param1', 'param2'));
  $this->_response->expects($this->once())
                  ->method('error')
                  ->with(404, $this->equalTo($method_info))
                  ->will($this->returnValue($error_response));
  $this->assertEquals($error_response, $this->_controller->invalid('param1', 'param2'));
 }
}

/* End of file contact_controller.test.php */
/* Location: ./WalkerCMS/tests/contact_controller.test.php */