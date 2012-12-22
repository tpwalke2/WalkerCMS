<?php
require_once(path('app') . 'helpers/nav_item_converter.php');
require_once(path('app') . 'tests/stubs/page_model.stub.php');
require_once(path('app') . 'tests/stubs/custom_nav_content.stub.php');

class TestNavItemConverter extends PHPUnit_Framework_TestCase
{
 private $_converter = null;
 private $_pages = null;
 private $_page = null;
 private $_current_page = null;

 protected function setUp()
 {
  $this->_converter = new NavItemConverter(new CustomNavContentRetriever_Stub('Custom Nav Content'));
  $this->_pages = array();
  $this->_page = new PageModel_Stub(array(
    'id'           => 'a_page_id',
    'page_title'   => 'The Page Title',
    'menu_title'   => 'Menu Title',
    'external_url' => '',
    'override_url' => '',
    'nav'          => true,
  ));
  $this->_current_page = new PageModel_Stub(array(
    'id' => 'other_id',
    'parent' => '',
  ));
 }

 public function testConvertPageID()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('a_page_id', $result['page_id']);
 }

 public function testTooltipFromPageTitle()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('The Page Title', $result['tooltip']);
 }

 public function testTooltipFromMenuTitleIfPageTitleEmpty()
 {
  $this->_page->set_option('page_title', '');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('Menu Title', $result['tooltip']);
 }

 public function testTooltipHasHTMLEncodedPageTitle()
 {
  $this->_page->set_option('page_title', 'Law & Order');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('Law &amp; Order', $result['tooltip']);
 }

 public function testTooltipHasHTMLEncodedMenuTitle()
 {
  $this->_page->set_option('page_title', '');
  $this->_page->set_option('menu_title', '2 > 1');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('2 &gt; 1', $result['tooltip']);
 }

 public function testDescriptionFromMenuTitle()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('Menu Title', $result['description']);
 }

 public function testDescriptionFromPageTitleIfMenuTitleEmpty()
 {
  $this->_page->set_option('menu_title', '');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('The Page Title', $result['description']);
 }

 public function testDescriptionHasHTMLEncodedMenuTitle()
 {
  $this->_page->set_option('menu_title', '2 > 1 & 3 < 4');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('2 &gt; 1 &amp; 3 &lt; 4', $result['description']);
 }

 public function testDescriptionHasHTMLEncodedPageTitle()
 {
  $this->_page->set_option('menu_title', '');
  $this->_page->set_option('page_title', 'Law & Order > CSI');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('Law &amp; Order &gt; CSI', $result['description']);
 }

 public function testExternalURLIsEmpty()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['is_external']);
 }

 public function testExternalURLIsFilled()
 {
  $this->_page->set_option('external_url', 'http://www.example.com');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertTrue($result['is_external']);
 }

 public function testOverrideURLIsEmpty()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['is_override']);
 }

 public function testOverrideURLIsFilled()
 {
  $this->_page->set_option('override_url', 'other_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertTrue($result['is_override']);
 }

 public function testURLComesFromPageID()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('a_page_id', $result['url']);
 }

 public function testExternalURL()
 {
  $this->_page->set_option('external_url', 'http://www.example.com');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('http://www.example.com', $result['url']);
 }

 public function testOverrideURL()
 {
  $this->_page->set_option('override_url', 'other_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('other_page_id', $result['url']);
 }

 public function testExternalURLPrecedenceOverOverrideURL()
 {
  $this->_page->set_option('external_url', 'http://www.example.com');
  $this->_page->set_option('override_url', 'other_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('http://www.example.com', $result['url']);
 }

 public function testCustom_ProcessedPageHasCustomNav()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertTrue($result['has_custom_content']);
 }

 public function testCustomContent_ProcessedPageHasCustomNav()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('Custom Nav Content', $result['custom_content']);
 }

 public function testActiveSection_HasCustomContent()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['is_active_section']);
 }

 public function testGenerateLink_HasCustomContent()
 {
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['generate_link']);
 }

 public function testActiveSection_NoCustomContent_ProcessedPageIsNotCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['is_active_section']);
 }

 public function testActiveSection_NoCustomContent_CurrentPageIsProcessedPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_page);
  $this->assertTrue($result['is_active_section']);
 }

 public function testGenerateLink_NoCustomContent_ProcessedPageIsNotCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertTrue($result['generate_link']);
 }

 public function testGenerateLink_NoCustomContent_ProcessedPageIsCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_page);
  $this->assertFalse($result['generate_link']);
 }

 public function testCustom_NoCustomContent_ProcessedPageIsCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_page);
  $this->assertFalse($result['has_custom_content']);
 }

 public function testCustomContent_NoCustomContent_ProcessedPageIsCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_page);
  $this->assertEquals('', $result['custom_content']);
 }

 public function testCustom_NoCustomContent_ProcessedPageIsNotCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['has_custom_content']);
 }

 public function testCustomContent_NoCustomContent_ProcessedPageIsNotCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('', $result['custom_content']);
 }

 public function testActiveSection_NoCustomContent_ProcessedPageIsChildOfCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $this->_current_page->set_option('parent', 'a_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertTrue($result['is_active_section']);
 }

 public function testGenerateLink_NoCustomContent_ProcessedPageIsChildOfCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $this->_current_page->set_option('parent', 'a_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertTrue($result['generate_link']);
 }

 public function testIsCustom_NoCustomContent_ProcessedPageIsChildOfCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $this->_current_page->set_option('parent', 'a_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertFalse($result['has_custom_content']);
 }

 public function testCustomContent_NoCustomContent_ProcessedPageIsChildOfCurrentPage()
 {
  $this->_page->set_option('nav', false);
  $this->_current_page->set_option('parent', 'a_page_id');
  $result = $this->_converter->convert($this->_pages, $this->_page, $this->_current_page);
  $this->assertEquals('', $result['custom_content']);
 }
}

/* End of file nav_item_converter.test.php */
/* Location: ./WalkerCMS/tests/nav_item_converter.test.php */