<?php
require_once(path('app') . 'tests/stubs/page_model.stub.php');

class TestPageModelStub extends PHPUnit_Framework_TestCase
{
 private $_stub = null;

 protected function setUp()
 {
  $this->_stub = new PageModel_Stub(array('id' => 'home'));
 }

 public function testID()
 {
  $this->assertEquals('home', $this->_stub->get_id());
 }

 public function testDefaultContent()
 {
  $this->assertFalse($this->_stub->has_content());
 }

 public function testDefaultSecondaryContent()
 {
  $this->assertFalse($this->_stub->has_secondary_content());
 }

 public function testDefaultCustomNav()
 {
  $this->assertFalse($this->_stub->has_custom_nav());
 }

 public function testDefaultCustomSubNav()
 {
  $this->assertFalse($this->_stub->has_custom_sub_nav());
 }

 public function testDefaultCustomJS()
 {
  $this->assertFalse($this->_stub->has_custom_js());
 }

 public function testDefaultCustomCSS()
 {
  $this->assertFalse($this->_stub->has_custom_css());
 }

 public function testDefaultCustomHTMLHeader()
 {
  $this->assertFalse($this->_stub->has_custom_html_header());
 }

 public function testDefaultCustomPageHeader()
 {
  $this->assertFalse($this->_stub->has_custom_page_header());
 }

 public function testDefaultCustomFooter()
 {
  $this->assertFalse($this->_stub->has_custom_footer());
 }

 public function testDefaultPageTitle()
 {
  $this->assertEquals('', $this->_stub->get_page_title());
 }

 public function testDefaultMenuTitle()
 {
  $this->assertEquals('', $this->_stub->get_menu_title());
 }

 public function testDefaultShowInNav()
 {
  $this->assertFalse($this->_stub->get_show_in_nav());
 }

 public function testDefaultExternalURL()
 {
  $this->assertEquals('', $this->_stub->get_external_url());
 }

 public function testDefaultOverrideURL()
 {
  $this->assertEquals('', $this->_stub->get_override_url());
 }

 public function testDefaultParent()
 {
  $this->assertEquals('', $this->_stub->get_parent());
 }

 public function testDefaultSubNavOnPage()
 {
  $this->assertFalse($this->_stub->get_sub_nav_on_page());
 }

 public function testDefaultPerformCaching()
 {
  $this->assertFalse($this->_stub->get_perform_caching());
 }

 public function testContent_SetFalse()
 {
  $this->_stub->set_option('content', false);
  $this->assertFalse($this->_stub->has_content());
 }

 public function testSecondaryContent_SetFalse()
 {
  $this->_stub->set_option('secondarycontent', false);
  $this->assertFalse($this->_stub->has_secondary_content());
 }

 public function testCustomNav_SetFalse()
 {
  $this->_stub->set_option('nav', false);
  $this->assertFalse($this->_stub->has_custom_nav());
 }

 public function testCustomSubNav_SetFalse()
 {
  $this->_stub->set_option('subnav', false);
  $this->assertFalse($this->_stub->has_custom_sub_nav());
 }

 public function testCustomJS_SetFalse()
 {
  $this->_stub->set_option('scripts', false);
  $this->assertFalse($this->_stub->has_custom_js());
 }

 public function testCustomCSS_SetFalse()
 {
  $this->_stub->set_option('styles', false);
  $this->assertFalse($this->_stub->has_custom_css());
 }

 public function testCustomHTMLHeader_SetFalse()
 {
  $this->_stub->set_option('htmlheaders', false);
  $this->assertFalse($this->_stub->has_custom_html_header());
 }

 public function testCustomPageHeader_SetFalse()
 {
  $this->_stub->set_option('headers', false);
  $this->assertFalse($this->_stub->has_custom_page_header());
 }

 public function testCustomFooter_SetFalse()
 {
  $this->_stub->set_option('footers', false);
  $this->assertFalse($this->_stub->has_custom_footer());
 }

 public function testPageTitle_SetEmpty()
 {
  $this->_stub->set_option('page_title', '');
  $this->assertEquals('', $this->_stub->get_page_title());
 }

 public function testMenuTitle_SetEmpty()
 {
  $this->_stub->set_option('menu_title', '');
  $this->assertEquals('', $this->_stub->get_menu_title());
 }

 public function testShowInNav_SetFalse()
 {
  $this->_stub->set_option('show_in_nav', false);
  $this->assertFalse($this->_stub->get_show_in_nav());
 }

 public function testExternalURL_SetEmpty()
 {
  $this->_stub->set_option('external_url', '');
  $this->assertEquals('', $this->_stub->get_external_url());
 }

 public function testOverrideURL_SetEmpty()
 {
  $this->_stub->set_option('override_url', '');
  $this->assertEquals('', $this->_stub->get_override_url());
 }

 public function testParent_SetEmpty()
 {
  $this->_stub->set_option('parent', '');
  $this->assertEquals('', $this->_stub->get_parent());
 }

 public function testSubNavOnPage_SetFalse()
 {
  $this->_stub->set_option('sub_nav_on_page', false);
  $this->assertFalse($this->_stub->get_sub_nav_on_page());
 }

 public function testPerformCaching_SetFalse()
 {
  $this->_stub->set_option('perform_caching', false);
  $this->assertFalse($this->_stub->get_perform_caching());
 }

 public function testContent_SetTrue()
 {
  $this->_stub->set_option('content', true);
  $this->assertTrue($this->_stub->has_content());
 }

 public function testSecondaryContent_SetTrue()
 {
  $this->_stub->set_option('secondarycontent', true);
  $this->assertTrue($this->_stub->has_secondary_content());
 }

 public function testCustomNav_SetTrue()
 {
  $this->_stub->set_option('nav', true);
  $this->assertTrue($this->_stub->has_custom_nav());
 }

 public function testCustomSubNav_SetTrue()
 {
  $this->_stub->set_option('subnav', true);
  $this->assertTrue($this->_stub->has_custom_sub_nav());
 }

 public function testCustomJS_SetTrue()
 {
  $this->_stub->set_option('scripts', true);
  $this->assertTrue($this->_stub->has_custom_js());
 }

 public function testCustomCSS_SetTrue()
 {
  $this->_stub->set_option('styles', true);
  $this->assertTrue($this->_stub->has_custom_css());
 }

 public function testCustomHTMLHeader_SetTrue()
 {
  $this->_stub->set_option('htmlheaders', true);
  $this->assertTrue($this->_stub->has_custom_html_header());
 }

 public function testCustomPageHeader_SetTrue()
 {
  $this->_stub->set_option('headers', true);
  $this->assertTrue($this->_stub->has_custom_page_header());
 }

 public function testCustomFooter_SetTrue()
 {
  $this->_stub->set_option('footers', true);
  $this->assertTrue($this->_stub->has_custom_footer());
 }

 public function testPageTitle_SetNonEmpty()
 {
  $this->_stub->set_option('page_title', 'Home Page');
  $this->assertEquals('Home Page', $this->_stub->get_page_title());
 }

 public function testMenuTitle_SetNonEmpty()
 {
  $this->_stub->set_option('menu_title', 'Menu Title');
  $this->assertEquals('Menu Title', $this->_stub->get_menu_title());
 }

 public function testShowInNav_SetTrue()
 {
  $this->_stub->set_option('show_in_nav', true);
  $this->assertTrue($this->_stub->get_show_in_nav());
 }

 public function testExternalURL_SetNonEmpty()
 {
  $this->_stub->set_option('external_url', 'http://www.example.com');
  $this->assertEquals('http://www.example.com', $this->_stub->get_external_url());
 }

 public function testOverrideURL_SetNonEmpty()
 {
  $this->_stub->set_option('override_url', 'other_page_id');
  $this->assertEquals('other_page_id', $this->_stub->get_override_url());
 }

 public function testParent_SetNonEmpty()
 {
  $this->_stub->set_option('parent', 'parent_page');
  $this->assertEquals('parent_page', $this->_stub->get_parent());
 }

 public function testSubNavOnPage_SetTrue()
 {
  $this->_stub->set_option('sub_nav_on_page', true);
  $this->assertTrue($this->_stub->get_sub_nav_on_page());
 }

 public function testPerformCaching_SetTrue()
 {
  $this->_stub->set_option('perform_caching', true);
  $this->assertTrue($this->_stub->get_perform_caching());
 }
}