<?php
class ContactFormResultDataFactoryTest extends PHPUnit_Framework_TestCase
{
 private $_input = null;
 private $_request = null;
 private $_config = null;
 private $_logger = null;
 private $_factory = null;
 private $_config_expectations = null;
 private $_input_expectations = null;
 
 protected function setUp()
 {
  $this->_config_expectations = array();
  $this->_input_expectations = array();
  $this->_input = $this->getMock('IInputAdapter', array('all', 'get'));
  $this->_input->expects($this->any())
               ->method('get')
               ->will($this->returnCallback(array($this, 'input_get_callback')));
  $this->_request = $this->getMock('IRequestAdapter', array('ip_address', 'user_agent', 'is_ajax'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config->expects($this->any())
                ->method('get')
                ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_factory = new ContactFormResultDataFactory(
    $this->_input,
    $this->_request,
    $this->_config,
    $this->_logger
    );
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
 }
 
 public function input_get_callback($key = null, $default = null)
 {
  $this->assertNotNull($key);
  if (isset($this->_input_expectations[$key])) { return $this->_input_expectations[$key]; }
  return $default;
 }
 
 public function testCreate_CorrectSubmittingPageID()
 {
  $this->_config_expectations['walkercms.contact_page'] = 'contact';
  $this->_input_expectations['page_id'] = 'contact';
  $result = $this->_factory->create();
  $this->assertEquals('contact', $result['submitting_page_id']);
 }
 
 public function testCreate_DifferentSubmittingPageID()
 {
  $this->_config_expectations['walkercms.contact_page'] = 'contact';
  $this->_input_expectations['page_id'] = 'home';
  $result = $this->_factory->create();
  $this->assertEquals('home', $result['submitting_page_id']);
  
 }
 
 public function testCreate_CorrectSubmitterName()
 {
  $this->_input_expectations['name'] = 'Han Solo';
  $result = $this->_factory->create();
  $this->assertEquals('Han Solo', $result['submitter_name']);
 }
 
 public function testCreate_DifferentSubmitterName()
 {
  $this->_input_expectations['name'] = 'Jean Luc Picard';
  $result = $this->_factory->create();
  $this->assertEquals('Jean Luc Picard', $result['submitter_name']);
 }
 
 public function testCreate_CorrectSubmitterEmail()
 {
  $this->_input_expectations['email'] = 'someone@example.com';
  $result = $this->_factory->create();
  $this->assertEquals('someone@example.com', $result['submitter_email']);
 }
 
 public function testCreate_DifferentSubmitterEmail()
 {
  $this->_input_expectations['email'] = 'driver@pizzaplanet.com';
  $result = $this->_factory->create();
  $this->assertEquals('driver@pizzaplanet.com', $result['submitter_email']);
 }
 
 public function testCreate_CorrectMessage()
 {
  $this->_input_expectations['message'] = 'This is a message.';
  $result = $this->_factory->create();
  $this->assertEquals('This is a message.', $result['message']);
 }
 
 public function testCreate_DifferentMessage()
 {
  $this->_input_expectations['message'] = 'Give me a call at 867-5309.';
  $result = $this->_factory->create();
  $this->assertEquals('Give me a call at 867-5309.', $result['message']);
 }
 
 public function testCreate_CorrectSpamControl()
 {
  $this->_input_expectations['required_control'] = 'Nothing to see here';
  $result = $this->_factory->create();
  $this->assertEquals('Nothing to see here', $result['spam_control']);
 }
 
 public function testCreate_DifferentSpamControl()
 {
  $this->_input_expectations['required_control'] = 'Get rich quick';
  $result = $this->_factory->create();
  $this->assertEquals('Get rich quick', $result['spam_control']);
 }
 
 public function testCreate_CorrectIPAddress()
 {
  $this->_request->expects($this->once())
                 ->method('ip_address')
                 ->will($this->returnValue('127.0.0.1'));
  $result = $this->_factory->create();
  $this->assertEquals('127.0.0.1', $result['ip_address']);
 }
 
 public function testCreate_DifferentIPAddress()
 {
  $this->_request->expects($this->once())
                 ->method('ip_address')
                 ->will($this->returnValue('192.168.1.1'));
  $result = $this->_factory->create();
  $this->assertEquals('192.168.1.1', $result['ip_address']);
 }
 
 public function testCreate_CorrectUserAgent()
 {
  $this->_request->expects($this->once())
                 ->method('user_agent')
                 ->will($this->returnValue('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)'));
  $result = $this->_factory->create();
  $this->assertEquals('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', $result['user_agent']);
 }
 
 public function testCreate_DifferentUserAgent()
 {
  $this->_request->expects($this->once())
                 ->method('user_agent')
                 ->will($this->returnValue('Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405'));
  $result = $this->_factory->create();
  $this->assertEquals('Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405', $result['user_agent']);
 }
 
 public function testCreate_CorrectValidInput()
 {
  $result = $this->_factory->create();
  $this->assertFalse($result['valid_input']);
 }
 
 public function testCreate_CorrectOrganizationName()
 {
  $this->_config_expectations['walkercms.organization_name'] = 'WalkerCMS';
  $result = $this->_factory->create();
  $this->assertEquals('WalkerCMS', $result['organization_name']);
 }
 
 public function testCreate_DifferentOrganizationName()
 {
  $this->_config_expectations['walkercms.organization_name'] = 'ACME';
  $result = $this->_factory->create();
  $this->assertEquals('ACME', $result['organization_name']);
 }
 
 public function testCreate_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $result = $this->_factory->create();
 }
}

/* End of file contactformresultdatafactory.test.php */
/* Location: ./WalkerCMS/tests/contactformresultdatafactory.test.php */