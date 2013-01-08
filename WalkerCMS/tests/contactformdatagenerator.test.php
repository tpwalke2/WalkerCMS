<?php
class ContactFormDataGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_config = null;
 private $_view = null;
 private $_logger = null;
 private $_generator = null;
 private $_context = null;
 private $_current_page = null;
 
 protected function setUp()
 {
  $this->_view = $this->getMock('IViewAdapter', array('generate_view'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_generator = new ContactFormDataGenerator(
    $this->_config,
    $this->_view,
    $this->_logger
    );
  
  $this->_context = new AppContext();
  $this->_context->set_pages(array());
  $this->_current_page = new PageModel(array('id' => 'contact'));
  $this->_context->set_current_page($this->_current_page);
  $this->_context->set_content_source_page($this->_current_page);
 }
 
 public function testGenerateData_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
 
  $result = $this->_generator->generate_data($this->_current_page, $this->_context);
 }
 
 public function testGenerateData_ContactPageNotRegistered()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => $this->_current_page->get_id());
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.contact_page')
                ->will($this->returnValue(''));
  $this->_view->expects($this->never())
              ->method('generate_view');
 
  $result = $this->_generator->generate_data($this->_current_page, $this->_context);
 
  $this->assertFalse(isset($result['contact_form']));
 }
 
 public function testGenerateData_ContactPageRegistered()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => $this->_current_page->get_id());
  $form_data = array('submitter_name' => '', 'submitter_email' => '', 'message' => '', 'submitting_page_id' => 'contact');
  $generated_view = array('status' => 'generated');
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.contact_page')
                ->will($this->returnValue('contact'));
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form', $this->equalTo($form_data))
              ->will($this->returnValue($generated_view));
 
  $result = $this->_generator->generate_data($this->_current_page, $this->_context);
 
  $this->assertEquals($generated_view, $result['contact_form']);
 }
 
 public function testGenerateData_ContactPageIsDifferentThanCurrentPage()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => $this->_current_page->get_id());
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.contact_page')
                ->will($this->returnValue('home'));
  $this->_view->expects($this->never())
              ->method('generate_view');
 
  $result = $this->_generator->generate_data($this->_current_page, $this->_context);
 
  $this->assertFalse(isset($result['contact_form']));
 }
 
 public function testGenerateData_GetContactDataFromContext()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => $this->_current_page->get_id());
 
  $form_data = array('submitter_name' => 'Tom', 'submitter_email' => 'twalker@example.com', 'message' => '', 'submitting_page_id' => 'contact');
  $this->_context->set_contact_form_data($form_data);
  $generated_view = array('status' => 'generated');
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.contact_page')
                ->will($this->returnValue('contact'));
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact_form', $this->equalTo($form_data))
              ->will($this->returnValue($generated_view));
 
  $result = $this->_generator->generate_data($this->_current_page, $this->_context);
 
  $this->assertEquals($generated_view, $result['contact_form']);
 }
 
 public function testGenerateData_UseContactFormNameFromContext()
 {
  $inner_data = array('inclusion_type' => 'content', 'page_id' => $this->_current_page->get_id());
  $form_data = array('submitter_name' => '', 'submitter_email' => '', 'message' => '', 'submitting_page_id' => 'contact');
  $this->_context->set_contact_form_view('partials.contact.testing');
  $generated_view = array('status' => 'generated');
  $this->_config->expects($this->any())
                ->method('get')
                ->with('walkercms.contact_page')
                ->will($this->returnValue('contact'));
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('partials.contact.testing', $this->equalTo($form_data))
              ->will($this->returnValue($generated_view));
 
  $result = $this->_generator->generate_data($this->_current_page, $this->_context);
  $this->assertEquals($generated_view, $result['contact_form']);
 }
}

/* End of file contactformdatagenerator.test.php */
/* Location: ./WalkerCMS/tests/contactformdatagenerator.test.php */