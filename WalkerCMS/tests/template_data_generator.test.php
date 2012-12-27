<?php
require_once(path('app') . 'helpers/interfaces/required_determiner.php');
require_once(path('app') . 'helpers/interfaces/config_adapter.php');
require_once(path('app') . 'helpers/template_data_generator.php');

class TestTemplateDataGenerator extends PHPUnit_Framework_TestCase
{
 private $_required_determiner = null;
 private $_config_adapter = null;
 private $_generator = null;
 private $_page = null;
 private $_config_expectations = null;
 
 protected function setUp()
 {
  $this->_required_determiner = $this->getMock('IRequiredDeterminer', array('is_required'));
  $this->_config_adapter = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config_adapter->expects($this->any())
                        ->method('get')
                        ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_generator = new TemplateDataGenerator($this->_required_determiner, $this->_config_adapter);
  $this->_page = $this->getMock('PageModel', array('has_custom_html_header',
                                                   'has_custom_css',
                                                   'has_custom_js',
                                                   'has_secondary_content',
                                                   'has_custom_page_header',
                                                   'has_custom_sub_nav',
                                                   'has_custom_footer'), array(array(
                                                     'id' => 'home',
                                                     'page_title' => 'Home Page')));
  
  $this->_config_expectations = array('walkercms.organization_name' => 'WalkerCMS');
 }
 
 private function set_page_expectations($expectations)
 {
  foreach ($expectations as $method=>$value)
  {
   $this->_page->expects($this->any())
               ->method($method)
               ->will($this->returnValue($value));
  }
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
 }
 
 public function testGenerateData_CorrectSite()
 {
  $this->_config_expectations['walkercms.site'] = 'WalkerCMS';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS', $result['site']);
 }
 
 public function testGenerateData_DifferentSite()
 {
  $this->_config_expectations['walkercms.site'] = 'Northwind';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('Northwind', $result['site']);
 }
 
 public function testGenerateData_CorrectPageID()
 {
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('home', $result['page_id']);
 }
 
 public function testGenerateData_DifferentPageID()
 {
  $this->_page = $this->getMock('PageModel', array('has_custom_html_header',
                                                   'has_custom_css',
                                                   'has_custom_js',
                                                   'has_secondary_content',
                                                   'has_custom_page_header',
                                                   'has_custom_sub_nav',
                                                   'has_custom_footer'), array(array(
                                                     'id' => 'about')));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('about', $result['page_id']);
 }
 
 public function testGenerateData_PageTitle_NoPageTitle()
 {
  $this->_page = $this->getMock('PageModel', array('has_custom_html_header',
    'has_custom_css',
    'has_custom_js',
    'has_secondary_content',
    'has_custom_page_header',
    'has_custom_sub_nav',
    'has_custom_footer'), array(array(
      'id' => 'home',
      'page_title' => '')));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS', $result['page_title']);
 }
 
 public function testGenerateData_PageTitle_HasPageTitle()
 {
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS: Home Page', $result['page_title']);
 }
 
 public function testGenerateData_OrganizationFullTitle()
 {
  $this->_config_expectations['walkercms.organization_full_title'] = 'WalkerCMS, Inc.';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS, Inc.', $result['organization_full_title']);
 }
 
 public function testGenerateData_DifferentOrganizationFullTitle()
 {
  $this->_config_expectations['walkercms.organization_full_title'] = 'Northwind, LLC.';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('Northwind, LLC.', $result['organization_full_title']);
 }
 
 public function testGenerateData_OrganizationFullTitle_DefaultToOrganizationName()
 {
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS', $result['organization_full_title']);
 }
 
 public function testGenerateData_OrganizationName()
 {
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS', $result['organization_name']);
 }
 
 public function testGenerateData_DifferentOrganizationName()
 {
  $this->_config_expectations['walkercms.organization_name'] = 'Northwind, LLC.';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('Northwind, LLC.', $result['organization_name']);
 }
 
 public function testGenerateData_Slogan()
 {
  $this->_config_expectations['walkercms.organization_slogan'] = 'Have it your way, right away.';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('Have it your way, right away.', $result['organization_slogan']);
 }
 
 public function testGenerateData_DifferentSlogan()
 {
  $this->_config_expectations['walkercms.organization_slogan'] = 'The Heartbeat of America';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('The Heartbeat of America', $result['organization_slogan']);
 }
 
 public function testGenerateData_Description()
 {
  $this->_config_expectations['walkercms.description'] = 'WalkerCMS is a file-based CMS intended primarily for small websites.';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('WalkerCMS is a file-based CMS intended primarily for small websites.', $result['site_description']);
 }
 
 public function testGenerateData_DifferentDescription()
 {
  $this->_config_expectations['walkercms.description'] = 'We have been serving the area\'s pest control needs for over 25 years.';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('We have been serving the area\'s pest control needs for over 25 years.', $result['site_description']);
 }
 
 public function testGenerateData_Keywords()
 {
  $this->_config_expectations['walkercms.keywords'] = 'a few keywords';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('a few keywords', $result['site_keywords']);
 }
 
 public function testGenerateData_DifferentKeywords()
 {
  $this->_config_expectations['walkercms.keywords'] = 'some different keywords';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('some different keywords', $result['site_keywords']);
 }
 
 public function testGenerateData_HasCustomHTMLHeader()
 {
  $this->set_page_expectations(array('has_custom_html_header' => true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_page_specific_html_header']); 
 }
 
 public function testGenerateData_NoCustomHTMLHeader()
 {
  $this->set_page_expectations(array('has_custom_html_header' => false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_page_specific_html_header']);
 }
 
 public function testGenerateData_HasCustomCSS()
 {
  $this->set_page_expectations(array('has_custom_css' => true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_page_specific_stylesheet']);
 }
 
 public function testGenerateData_NoCustomCSS()
 {
  $this->set_page_expectations(array('has_custom_css' => false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_page_specific_stylesheet']);
 }
 
 public function testGenerateData_HasCustomJS()
 {
  $this->set_page_expectations(array('has_custom_js' => true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_page_specific_javascript']);
 }
 
 public function testGenerateData_NoCustomJS()
 {
  $this->set_page_expectations(array('has_custom_js' => false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_page_specific_javascript']);
 }
 
 public function testGenerateData_ShowIEWarning()
 {
  $this->_config_expectations['walkercms.show_ie_warning'] = true;
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['show_ie_warning']);
 }
 
 public function testGenerateData_DoNotShowIEWarning()
 {
  $this->_config_expectations['walkercms.show_ie_warning'] = false;
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['show_ie_warning']);
 }
 
 public function testGenerateData_IEWarningMinimumVersion()
 {
  $this->_config_expectations['walkercms.minimum_ie_version'] = '7';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('7', $result['minimum_ie_version']);
 }
 
 public function testGenerateData_DifferentIEWarningMinimumVersion()
 {
  $this->_config_expectations['walkercms.minimum_ie_version'] = '6';
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertEquals('6', $result['minimum_ie_version']);
 }
 
 public function testGenerateData_HasSecondaryContent()
 {
  $this->set_page_expectations(array('has_secondary_content' => true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_secondary_content']);
 }
 
 public function testGenerateData_NoSecondaryContent()
 {
  $this->set_page_expectations(array('has_secondary_content' => false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_secondary_content']);
 }
 
 public function testGenerateData_HasCustomPageHeader()
 {
  $this->set_page_expectations(array('has_custom_page_header' => true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_page_specific_header']);
 }
 
 public function testGenerateData_NoCustomPageHeader()
 {
  $this->set_page_expectations(array('has_custom_page_header' => false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_page_specific_header']);
 }
 
 public function testGenerateData_HasSubNav_HasRequiredSubNav()
 {
  $this->_required_determiner->expects($this->once())
                             ->method('is_required')
                             ->will($this->returnValue(true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_sub_nav']);
 }
 
 public function testGenerateData_HasSubNav_SubNavNotRequired()
 {
  $this->_required_determiner->expects($this->once())
                             ->method('is_required')
                             ->will($this->returnValue(false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_sub_nav']);
 }
 
 public function testGenerateData_HasCustomFooter()
 {
  $this->set_page_expectations(array('has_custom_footer' => true));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertTrue($result['has_page_specific_footer']);
 }
 
 public function testGenerateData_NoCustomFooter()
 {
  $this->set_page_expectations(array('has_custom_footer' => false));
  $result = $this->_generator->generate_data(null, $this->_page);
  $this->assertFalse($result['has_page_specific_footer']);
 }
}

/* End of file template_data_generator.test.php */
/* Location: ./WalkerCMS/tests/template_data_generator.test.php */