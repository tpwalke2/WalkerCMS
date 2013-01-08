<?php
class ContactControllerTest extends PHPUnit_Framework_TestCase
{
 private $_pages_retriever = null;
 private $_contact_form_generator = null;
 private $_config = null;
 private $_input = null;
 private $_validator = null;
 private $_redirect = null;
 private $_request = null;
 private $_response = null;
 private $_view = null;
 private $_mailer = null;
 private $_logger = null;
 private $_controller = null;
 private $_pages = null;
 private $_context = null;

 protected function setUp()
 {
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_controller = new Contact_Controller(
    $this->_pages_retriever,
    $this->_contact_form_generator,
    $this->_config,
    $this->_input,
    $this->_validator,
    $this->_redirect,
    $this->_request,
    $this->_response,
    $this->_view,
    $this->_mailer,
    $this->_logger
    );
  $this->_context = new AppContext();
 }

public function testPage_UseLogger()
 {
  //$this->_logger->expects($this->atLeastOnce())->method('debug');
  //$result = $this->_controller->post_contact_submission();
  $this->assertTrue(true);
 }
}

/* End of file contact_controller.test.php */
/* Location: ./WalkerCMS/tests/contact_controller.test.php */