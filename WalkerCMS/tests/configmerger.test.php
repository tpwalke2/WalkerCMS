<?php
class ConfigMergerTest extends PHPUnit_Framework_TestCase
{
 private $_merger = null;
 
 protected function setUp()
 {
  $this->_merger = new ConfigMerger();
 }
 
 public function testMerge_NothingToMerge()
 {
  $config = array('version' => '0.5');
  $pages = array();
  $page_defaults = array();
  
  $this->assertEquals($config, $this->_merger->merge($pages, $page_defaults, $config));
 }
 
 public function testMerge_OnePageGetsAddedToConfig()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home'));
  $page_defaults = array();
  
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals(1, count($result['pages']));
 }
 
 public function testMerge_TwoPageGetsAddedToConfig()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home'),
                 'about' => array('page_title' => 'Contact'));
  $page_defaults = array();
 
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals(2, count($result['pages']));
 }
 
 public function testMerge_OnePageGetsAddedToConfigWithCorrectIndex()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home'));
  $page_defaults = array();
 
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals('Home', $result['pages']['home']['page_title']);
 }
 
 public function testMerge_AllAttributesOfOnePageAreAddedToFinalResult()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home', 'menu_title' => 'Home', 'show_in_nav' => true));
  $page_defaults = array();
  
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals('Home', $result['pages']['home']['page_title']);
  $this->assertEquals('Home', $result['pages']['home']['menu_title']);
  $this->assertTrue($result['pages']['home']['show_in_nav']);
 }
 
 public function testMerge_SetID()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home', 'menu_title' => 'Home', 'show_in_nav' => true));
  $page_defaults = array();
 
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals('home', $result['pages']['home']['id']);
 }
 
 public function testMerge_MergeDefaults()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home', 'menu_title' => 'Home', 'show_in_nav' => true));
  $page_defaults = array('sub_nav_on_page' => false);
  
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertFalse($result['pages']['home']['sub_nav_on_page']);
 }
 
 public function testMerge_DefaultsDoNotTakePrecedence()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home', 'menu_title' => 'Home', 'show_in_nav' => true));
  $page_defaults = array('show_in_nav' => false);
 
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertTrue($result['pages']['home']['show_in_nav']);
 }
 
 public function testMerge_DefaultsDoNotOverwritePageID()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('page_title' => 'Home', 'menu_title' => 'Home', 'show_in_nav' => true));
  $page_defaults = array('id' => 'hello');
 
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals('home', $result['pages']['home']['id']);
 }
 
 public function testMerge_PageConfigDoesNotOverwritePageID()
 {
  $config = array('version' => '0.5');
  $pages = array('home' => array('id' => 'hello', 'page_title' => 'Home', 'menu_title' => 'Home', 'show_in_nav' => true));
  $page_defaults = array();
  
  $result = $this->_merger->merge($pages, $page_defaults, $config);
  $this->assertEquals('home', $result['pages']['home']['id']);
 }
}

/* End of file configmerger.test.php */
/* Location: ./WalkerCMS/tests/configmerger.test.php */