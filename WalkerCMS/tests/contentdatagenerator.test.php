<?php
class ContentDataGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_inner_data_generator = null;
 private $_contact_form_data_generator = null;
 private $_logger = null;
 private $_generator = null;
 private $_context = null;
 private $_working_page = null;
 private $_content_source_page = null;
 private $_config_expectations = null;
 
 protected function setUp()
 {
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config->expects($this->any())
                ->method('get')
                ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_inner_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_contact_form_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_generator = new ContentDataGenerator($this->_inner_data_generator, $this->_contact_form_data_generator, $this->_config, $this->_logger);
  $this->_context = new AppContext();
  $this->_working_page = new PageModel(array('id' => 'contact_parent'));
  $this->_content_source_page = new PageModel(array('id' => 'contact'));
  $this->_context->set_content_source_page($this->_content_source_page);
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
 }
 
 public function testGenerateData_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  
  $result = $this->_generator->generate_data($this->_working_page, $this->_context);
 }
 
 public function testGenerateData_ContactFormGenerated()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => 'contact');
  $this->_inner_data_generator->expects($this->once())
                              ->method('generate_data')
                              ->with($this->equalTo($this->_content_source_page),
                                     $this->equalTo($this->_context))
                              ->will($this->returnValue($inner_data));
  $contact_form_data = array('contact_form' => array());
  $this->_contact_form_data_generator->expects($this->once())
                                     ->method('generate_data')
                                     ->with($this->equalTo($this->_content_source_page),
                                            $this->equalTo($this->_context))
                                     ->will($this->returnValue($contact_form_data));
  $result = $this->_generator->generate_data($this->_working_page, $this->_context);
  
  $this->assertEquals('content', $result['inclusion_type']);
  $this->assertEquals('contact', $result['page_id']);
  $this->assertTrue(isset($result['contact_form']));
 }
 
 public function testGenerateData_ContactFormNotGenerated()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => 'contact');
  $this->_inner_data_generator->expects($this->once())
                              ->method('generate_data')
                              ->with($this->equalTo($this->_content_source_page),
                                     $this->equalTo($this->_context))
                              ->will($this->returnValue($inner_data));
  $this->_contact_form_data_generator->expects($this->once())
                                     ->method('generate_data')
                                     ->with($this->equalTo($this->_content_source_page),
                                            $this->equalTo($this->_context))
                                     ->will($this->returnValue(null));
  $result = $this->_generator->generate_data($this->_working_page, $this->_context);
  
  $this->assertEquals('content', $result['inclusion_type']);
  $this->assertEquals('contact', $result['page_id']);
  $this->assertFalse(isset($result['contact_form']));
 }
 
 public function testGenerateData_SendVersionFromConfig()
 {
  $this->_config_expectations['walkercms.version'] = '0.5';
  $inner_data = array('inclusion_type' => 'content', 'page_id' => 'contact');
  $this->_inner_data_generator->expects($this->once())
                              ->method('generate_data')
                              ->with($this->equalTo($this->_content_source_page),
                                     $this->equalTo($this->_context))
                              ->will($this->returnValue($inner_data));
  $this->_contact_form_data_generator->expects($this->once())
                                     ->method('generate_data')
                                     ->with($this->equalTo($this->_content_source_page),
                                            $this->equalTo($this->_context))
                                     ->will($this->returnValue(null));
  $result = $this->_generator->generate_data($this->_working_page, $this->_context);
 
  $this->assertEquals('0.5', $result['site_version']);
 }
}

/* End of file contentdatagenerator.test.php */
/* Location: ./WalkerCMS/tests/contentdatagenerator.test.php */