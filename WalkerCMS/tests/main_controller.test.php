<?php
class MainControllerTest extends PHPUnit_Framework_TestCase
{
 private $_context_factory = null;
 private $_page_generator = null;
 private $_config = null;
 private $_cache = null;
 private $_response = null;
 private $_logger = null;
 private $_controller = null;
 private $_pages = null;
 private $_context = null;
 private $_config_expectations = null;

 protected function setUp()
 {
  $this->_context_factory = $this->getMock('IContextFactory', array('create'));
  $this->_page_generator = $this->getMock('IPageGenerator', array('generate_page'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config->expects($this->any())
                ->method('get')
                ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_cache = $this->getMock('ICacheAdapter', array('has', 'get', 'put', 'remember', 'forget'));
  $this->_response = $this->getMock('IResponseAdapter', array('send_json', 'error'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_controller = new Main_Controller(
    $this->_context_factory,
    $this->_page_generator,
    $this->_config,
    $this->_cache,
    $this->_response,
    $this->_logger
    );
  
  $this->_pages = array('home' => new PageModel(array('id' => 'home', 'perform_caching' => false)));
  $this->_context = new AppContext();
  $this->_context->set_pages($this->_pages);
  $this->_context->set_current_page($this->_pages['home']);
  
  $this->_config_expectations = array(
    'walkercms.contact_page' => 'contact',
    'walkercms.page_cache_expiration' => 360,
    'walkercms.hash' => 'ConfigHash');
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
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
  
  $result = $this->_controller->action_page('home');
 }
 
 public function testPage_PageRequiresCaching_PageInCache()
 {
  $cached_page = array('status' => 'cached');
  $this->_context->set_current_page(new PageModel(array('id' => 'about', 'perform_caching' => true)));
  
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('about')
                         ->will($this->returnValue($this->_context));
  $this->_cache->expects($this->once())
               ->method('has')
               ->with($this->equalTo('view_about_ConfigHash'))
               ->will($this->returnValue(true));
  $this->_cache->expects($this->once())
               ->method('get')
               ->with($this->equalTo('view_about_ConfigHash'))
               ->will($this->returnValue($cached_page));
  $this->_page_generator->expects($this->never())
                        ->method('generate_page');
  
  $result = $this->_controller->action_page('about');
  $this->assertSame($cached_page, $result);
 }
 
 public function testPage_NonContactPageDoesNotRequireCaching_PageAlreadyInCache()
 {
  $generated_page = array('status' => 'generated');
  
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('about')
                         ->will($this->returnValue($this->_context));
  $this->_cache->expects($this->any())
               ->method('has')
               ->with('view_about_ConfigHash')
               ->will($this->returnValue(true));
  $this->_cache->expects($this->never())->method('get');
  $this->_page_generator->expects($this->once())
                        ->method('generate_page')
                        ->with($this->_context)
                        ->will($this->returnValue($generated_page));
  $this->_cache->expects($this->never())->method('put');
  
  $result = $this->_controller->action_page('about');
  $this->assertSame($generated_page, $result);
 }
 
 public function testPage_ContactPage_RequireCachingIgnored_PageAlreadyInCache()
 {
  $generated_page = array('status' => 'generated');
  $this->_context->set_current_page(new PageModel(array('id' => 'contact', 'perform_caching' => true)));
 
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('contact')
                         ->will($this->returnValue($this->_context));
  $this->_cache->expects($this->any())
               ->method('has')
               ->with('view_contact_ConfigHash')
               ->will($this->returnValue(true));
  $this->_cache->expects($this->never())->method('get');
  $this->_page_generator->expects($this->once())
                        ->method('generate_page')
                        ->with($this->_context)
                        ->will($this->returnValue($generated_page));
  $this->_cache->expects($this->never())->method('put');
  
  $result = $this->_controller->action_page('contact');
  $this->assertSame($generated_page, $result);
 }
 
 public function testPage_PerformCaching_NotInCache()
 {
  $generated_page = $this->getMock('IViewWrapper', array('render'));
  $generated_page->expects($this->once())
                 ->method('render')
                 ->will($this->returnValue('Rendered Page'));
  $this->_context->set_current_page(new PageModel(array('id' => 'about', 'perform_caching' => true)));
  
  $this->_context_factory->expects($this->once())
                         ->method('create')
                         ->with('about')
                         ->will($this->returnValue($this->_context));
  $this->_cache->expects($this->any())
               ->method('has')
               ->with('view_about_ConfigHash')
               ->will($this->returnValue(false));
  $this->_cache->expects($this->never())->method('get');
  $this->_page_generator->expects($this->once())
                        ->method('generate_page')
                        ->with($this->_context)
                        ->will($this->returnValue($generated_page));
  $this->_cache->expects($this->once())
               ->method('put')
               ->with('view_about_ConfigHash', 'Rendered Page', 360);
  
  $result = $this->_controller->action_page('about');
  $this->assertEquals('Rendered Page', $result);
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

/* End of file main_controller.test.php */
/* Location: ./WalkerCMS/tests/main_controller.test.php */