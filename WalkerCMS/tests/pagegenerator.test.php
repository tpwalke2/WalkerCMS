<?php
class PageGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_template_data_generator = null;
 private $_site_html_header_data_generator = null;
 private $_page_html_header_data_generator = null;
 private $_page_header_data_generator = null;
 private $_content_data_generator = null;
 private $_nav_data_generator = null;
 private $_sub_nav_data_generator = null;
 private $_custom_sub_nav_data_generator = null;
 private $_secondary_content_data_generator = null;
 private $_footer_data_generator = null;
 private $_logger = null;
 
 private $_generator = null;
 private $_context = null;
 private $_current_page = null;
 private $_allowed_views = array(
   'layouts.common' => '',
   'partials.page_inclusion' => '',
   'partials.nav_template' => '',
   'partials.nav' => '');
 private $_data_types = array(
   'site_html_header',
   'page_html_header',
   'page_header',
   'content',
   'nav',
   'sub_nav',
   'custom_sub_nav',
   'secondary_content',
   'footer');
 
 protected function setUp()
 {
  $this->_template_data_generator = $this->getMock('ITemplateDataGenerator', array('generate_data'));
  $this->_template_data_generator->expects($this->any())
                                 ->method('generate_data')
                                 ->will($this->returnValue(array('name' => 'template_data')));
  $this->_site_html_header_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_page_html_header_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_page_header_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_content_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_sub_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_custom_sub_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_secondary_content_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_footer_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_view = $this->getMock('IViewAdapter', array('generate_view'));
  $this->_view->expects($this->any())
              ->method('generate_view')
              ->will($this->returnCallback(array($this, 'generate_view_callback')));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_current_page = $this->getMock('PageModel', array('has_custom_sub_nav'), array(array('id' => 'home')));
  $this->_context = new AppContext();
  $this->_context->set_current_page($this->_current_page);
    
  $this->_generator = new PageGenerator($this->_template_data_generator,
                                        $this->_site_html_header_data_generator,
                                        $this->_page_html_header_data_generator,
                                        $this->_page_header_data_generator,
                                        $this->_content_data_generator,
                                        $this->_nav_data_generator,
                                        $this->_sub_nav_data_generator,
                                        $this->_custom_sub_nav_data_generator,
                                        $this->_secondary_content_data_generator,
                                        $this->_footer_data_generator,
                                        $this->_view,
                                        $this->_logger);
  
  $this->set_data_generator_expectations();
 }
 
 private function set_data_generator_expectations()
 {
  foreach ($this->_data_types as $type)
  {
   $object_name = "_{$type}_data_generator";
   $this->$object_name->expects($this->any())
                      ->method('generate_data')
                      ->will($this->returnValue(array('name' => "{$type}_data")));
  }
 }

 public function testGeneratePage_GetTemplateData()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('layouts.common', $result['view_name']);
  $this->assertEquals('template_data', $result['view_data']['name']);
 }
 
 public function testGeneratePage_GetSiteHTMLHeaderData()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['site_specific_html_header']['view_name']);
  $this->assertEquals('site_html_header_data', $result['site_specific_html_header']['view_data']['name']);
 }
 
 public function testGeneratePage_GetPageHTMLHeaderData()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['page_specific_html_header']['view_name']);
  $this->assertEquals('page_html_header_data', $result['page_specific_html_header']['view_data']['name']);
 }
 
 public function testGeneratePage_GetPageHeaderData()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['page_specific_header']['view_name']);
  $this->assertEquals('page_header_data', $result['page_specific_header']['view_data']['name']);
 }
 
 public function testGeneratePage_GetPageContent()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['page_content']['view_name']);
  $this->assertEquals('content_data', $result['page_content']['view_data']['name']);
 }
 
 public function testGeneratePage_GetSecondaryContent()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['secondary_content']['view_name']);
  $this->assertEquals('secondary_content_data', $result['secondary_content']['view_data']['name']);
 }
 
 public function testGeneratePage_GetFooter()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['page_specific_footer']['view_name']);
  $this->assertEquals('footer_data', $result['page_specific_footer']['view_data']['name']);
 }
 
 public function testGeneratePage_GetNav()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.nav_template', $result['nav']['view_name']);
  $this->assertEquals('partials.nav', $result['nav']['view_data']['nav_items']['view_name']);
  $this->assertEquals('nav_data', $result['nav']['view_data']['nav_items']['view_data']['name']);
 }
 
 public function testGeneratePage_GetStandardSubNav()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(false));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.nav_template', $result['sub_nav']['view_name']);
  $this->assertEquals('partials.nav', $result['sub_nav']['view_data']['nav_items']['view_name']);
  $this->assertEquals('sub_nav_data', $result['sub_nav']['view_data']['nav_items']['view_data']['name']);
 }
 
 public function testGeneratePage_GetCustomSubNav()
 {
  $this->_current_page->expects($this->once())
                      ->method('has_custom_sub_nav')
                      ->will($this->returnValue(true));
  $result = $this->_generator->generate_page($this->_context);
  $this->assertEquals('partials.page_inclusion', $result['sub_nav']['view_name']);
  $this->assertEquals('custom_sub_nav_data', $result['sub_nav']['view_data']['name']);
 }
 
 public function generate_view_callback($view_name, $view_data)
 {
  $this->assertTrue(isset($this->_allowed_views[$view_name]));
  return compact('view_name', 'view_data');
 }
}

/* End of file pagegenerator.test.php */
/* Location: ./WalkerCMS/tests/pagegenerator.test.php */