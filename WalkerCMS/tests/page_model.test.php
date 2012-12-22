<?php
require_once(path('app') . 'entities/page_model.php');

class TestPageModel extends PHPUnit_Framework_TestCase
{
 private $_page_settings = null;

 protected function setUp()
 {
  $this->_page_settings = array(
    'id' => 'home',
    'page_title' => 'Page Title',
    'menu_title' => 'Menu Title',
    'show_in_nav' => true,
    'parent'          => 'parent_page',
    'external_url'    => 'http://www.example.com',
    'override_url'    => 'other_page_id',
    'sub_nav_on_page' => true,
    'perform_caching' => true
  );
 }

 public function testInitializePageModel_ValidID()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('home', $page->get_id());
 }

 public function testInitializePageModel_TrimIDWhitespace()
 {
  $this->_page_settings['id'] = ' home ';
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('home', $page->get_id());
 }

 /**
  * @expectedException InvalidArgumentException
  */
 public function testInitializePageModel_EmptyID()
 {
  $this->_page_settings['id'] = '';
  $page = new PageModel($this->_page_settings);
 }

 /**
  * @expectedException InvalidArgumentException
  */
 public function testInitializePageModel_AllWhitespaceID()
 {
  $this->_page_settings['id'] = '     ';
  $page = new PageModel($this->_page_settings);
 }

 public function testGetPageTitle()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('Page Title', $page->get_page_title());
 }

 public function testGetMenuTitle()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('Menu Title', $page->get_menu_title());
 }

 public function testShowInNav_Visible()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertTrue($page->get_show_in_nav());
 }

 public function testShowInNav_Invisible()
 {
  $this->_page_settings['show_in_nav'] = false;
  $page = new PageModel($this->_page_settings);
  $this->assertFalse($page->get_show_in_nav());
 }

 public function testGetParent()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('parent_page', $page->get_parent());
 }

 public function testGetExternalURL()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('http://www.example.com', $page->get_external_url());
 }

 public function testGetOverrideURL()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertEquals('other_page_id', $page->get_override_url());
 }

 public function testGetSubNavOnPage()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertTrue($page->get_sub_nav_on_page());
 }

 public function testGetPerformCaching()
 {
  $page = new PageModel($this->_page_settings);
  $this->assertTrue($page->get_perform_caching());
 }
}