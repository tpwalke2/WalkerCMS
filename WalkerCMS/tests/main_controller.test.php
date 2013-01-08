<?php
class MainControllerTest extends PHPUnit_Framework_TestCase
{
 private $_context_factory = null;
 private $_page_generator = null;
 private $_config = null;
 private $_cache = null;
 private $_logger = null;
 private $_controller = null;
 private $_pages = null;
 private $_context = null;

 protected function setUp()
 {
  $this->_context_factory = $this->getMock('IContextFactory', array('create'));
  $this->_page_generator = $this->getMock('IPageGenerator', array('generate_page'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_cache = $this->getMock('ICacheAdapter', array('has', 'get', 'put', 'remember', 'forget'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_controller = new Main_Controller(
    $this->_context_factory,
    $this->_page_generator,
    $this->_config,
    $this->_cache,
    $this->_logger
    );
  
  $this->_pages = array('home' => new PageModel(array('id' => 'home', 'perform_caching' => false)));
  $this->_context = new AppContext();
  $this->_context->set_pages($this->_pages);
  $this->_context->set_current_page($this->_pages['home']);
 }

 public function testPage_UseLogger()
 {
  $generated_page = array('status' => 'generated');
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('home')
                         ->will($this->returnValue($this->_context));
  $this->_page_generator->expects($this->once())
                        ->method('generate_page')
                        ->with($this->_context)
                        ->will($this->returnValue($generated_page));
  
  $result = $this->_controller->action_page('home', $this->_context);
 }
}

/* End of file main_controller.test.php */
/* Location: ./WalkerCMS/tests/main_controller.test.php */