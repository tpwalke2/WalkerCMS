<?php
class TemplateDataGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_required_determiner = null;
 private $_config = null;
 private $_logger = null;
 private $_generator = null;
 private $_context = null;
 private $_site = null;
 private $_current_page = null;
 private $_content_source_page = null;
 private $_config_expectations = null;
 
 protected function setUp()
 {
  $this->_required_determiner = $this->getMock('IRequiredDeterminer', array('is_required'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config->expects($this->any())
                ->method('get')
                ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_generator = new TemplateDataGenerator($this->_required_determiner, $this->_config, $this->_logger);
  $this->_site = $this->getMock('SiteModel', array('has_custom_html_header'));
  $this->_current_page = $this->getMock('PageModel', array('has_custom_html_header',
                                                            'has_custom_css',
                                                            'has_custom_js',
                                                            'has_secondary_content',
                                                            'has_custom_page_header',
                                                            'has_custom_sub_nav',
                                                            'has_custom_footer'), array(array(
                                                              'id' => 'home',
                                                              'page_title' => 'Home Page')));
  $this->_content_source_page = new PageModel(array('id' => 'about'));
  $this->_context = new AppContext();
  $this->_context->set_site($this->_site);
  $this->_context->set_current_page($this->_current_page);
  $this->_context->set_content_source_page($this->_content_source_page);
  
  $this->_config_expectations = array('walkercms.organization_name' => 'WalkerCMS');
 }
 
 private function set_page_expectations($expectations)
 {
  foreach ($expectations as $method=>$value)
  {
   $this->_current_page->expects($this->any())
                       ->method($method)
                       ->will($this->returnValue($value));
  }
 }
 
 private function set_site_expectations($expectations)
 {
  foreach ($expectations as $method=>$value)
  {
   $this->_site->expects($this->any())
   ->method($method)
   ->will($this->returnValue($value));
  }
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_generator->generate_data($this->_context);
 }
 
 public function testGenerateData_CorrectSite()
 {
  $this->_config_expectations['walkercms.site'] = 'WalkerCMS';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS', $result['site']);
 }
 
 public function testGenerateData_DifferentSite()
 {
  $this->_config_expectations['walkercms.site'] = 'Northwind';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Northwind', $result['site']);
 }
 
 public function testGenerateData_CorrectPageID()
 {
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('home', $result['page_id']);
 }
 
 public function testGenerateData_DifferentPageID()
 {
  $this->_current_page = $this->getMock('PageModel', array('has_custom_html_header',
                                                   'has_custom_css',
                                                   'has_custom_js',
                                                   'has_secondary_content',
                                                   'has_custom_page_header',
                                                   'has_custom_sub_nav',
                                                   'has_custom_footer'), array(array(
                                                     'id' => 'about')));
  $this->_context->set_current_page($this->_current_page);
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('about', $result['page_id']);
 }
 
 public function testGenerateData_PageTitle_NoPageTitle()
 {
  $this->_current_page = $this->getMock('PageModel', array('has_custom_html_header',
                                                           'has_custom_css',
                                                           'has_custom_js',
                                                           'has_secondary_content',
                                                           'has_custom_page_header',
                                                           'has_custom_sub_nav',
                                                           'has_custom_footer'), array(array(
                                                             'id' => 'home',
                                                             'page_title' => '')));
  $this->_context->set_current_page($this->_current_page);
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS', $result['page_title']);
 }
 
 public function testGenerateData_PageTitle_HasPageTitle()
 {
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS: Home Page', $result['page_title']);
 }
 
 public function testGenerateData_PageTitle_HasPageTitle_HTMLEncoded()
 {
  $this->_config_expectations['walkercms.organization_name'] = 'Dewey, Cheatham, & Howe';
  $this->_current_page = $this->getMock('PageModel', array('has_custom_html_header',
                                                           'has_custom_css',
                                                           'has_custom_js',
                                                           'has_secondary_content',
                                                           'has_custom_page_header',
                                                           'has_custom_sub_nav',
                                                           'has_custom_footer'), array(array(
                                                             'id' => 'home',
                                                             'page_title' => 'Services & Benefits')));
  $this->_context->set_current_page($this->_current_page);
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Dewey, Cheatham, &amp; Howe: Services &amp; Benefits', $result['page_title']);
 }
 
 public function testGenerateData_OrganizationFullTitle()
 {
  $this->_config_expectations['walkercms.organization_full_title'] = 'WalkerCMS, Inc.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS, Inc.', $result['organization_full_title']);
 }
 
 public function testGenerateData_OrganizationFullTitleIsHTMLEncoded()
 {
  $this->_config_expectations['walkercms.organization_full_title'] = 'Dewey, Cheatham, & Howe, Inc.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Dewey, Cheatham, &amp; Howe, Inc.', $result['organization_full_title']);
 }
 
 public function testGenerateData_DifferentOrganizationFullTitle()
 {
  $this->_config_expectations['walkercms.organization_full_title'] = 'Northwind, LLC.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Northwind, LLC.', $result['organization_full_title']);
 }
 
 public function testGenerateData_OrganizationFullTitle_DefaultToOrganizationName()
 {
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS', $result['organization_full_title']);
 }
 
 public function testGenerateData_OrganizationName()
 {
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS', $result['organization_name']);
 }
 
 public function testGenerateData_OrganizationNameIsHTMLEncoded()
 {
  $this->_config_expectations['walkercms.organization_name'] = 'Dewey, Cheatham, & Howe';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Dewey, Cheatham, &amp; Howe', $result['organization_name']);
 }
 
 public function testGenerateData_DifferentOrganizationName()
 {
  $this->_config_expectations['walkercms.organization_name'] = 'Northwind, LLC.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Northwind, LLC.', $result['organization_name']);
 }
 
 public function testGenerateData_Slogan()
 {
  $this->_config_expectations['walkercms.organization_slogan'] = 'Have it your way, right away.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Have it your way, right away.', $result['organization_slogan']);
 }
 
 public function testGenerateData_Slogan_HTMLEncoded()
 {
  $this->_config_expectations['walkercms.organization_slogan'] = 'Family owned & operated.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('Family owned &amp; operated.', $result['organization_slogan']);
 }
 
 public function testGenerateData_DifferentSlogan()
 {
  $this->_config_expectations['walkercms.organization_slogan'] = 'The Heartbeat of America';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('The Heartbeat of America', $result['organization_slogan']);
 }
 
 public function testGenerateData_Description()
 {
  $this->_config_expectations['walkercms.description'] = 'WalkerCMS is a file-based CMS intended primarily for small websites.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('WalkerCMS is a file-based CMS intended primarily for small websites.', $result['site_description']);
 }
 
 public function testGenerateData_DifferentDescription()
 {
  $this->_config_expectations['walkercms.description'] = 'We have been serving the area\'s pest control needs for over 25 years.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('We have been serving the area\'s pest control needs for over 25 years.', $result['site_description']);
 }
 
 public function testGenerateData_DescriptionIsHTMLEncoded()
 {
  $this->_config_expectations['walkercms.description'] = 'A family-owned & operated business for over 30 years.';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('A family-owned &amp; operated business for over 30 years.', $result['site_description']);
 }
 
 public function testGenerateData_Keywords()
 {
  $this->_config_expectations['walkercms.keywords'] = 'a few keywords';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('a few keywords', $result['site_keywords']);
 }
 
 public function testGenerateData_KeywordsAreHTMLEncoded()
 {
  $this->_config_expectations['walkercms.keywords'] = 'including "quotes" & ampersands';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('including &quot;quotes&quot; &amp; ampersands', $result['site_keywords']);
 }
 
 public function testGenerateData_DifferentKeywords()
 {
  $this->_config_expectations['walkercms.keywords'] = 'some different keywords';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('some different keywords', $result['site_keywords']);
 }
 
 public function testGenerateData_HasCustomSiteHTMLHeader()
 {
  $this->set_site_expectations(array('has_custom_html_header' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_site_specific_html_header']);
 }
 
 public function testGenerateData_NoCustomSiteHTMLHeader()
 {
  $this->set_site_expectations(array('has_custom_html_header' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_site_specific_html_header']);
 }
 
 public function testGenerateData_HasCustomPageHTMLHeader()
 {
  $this->set_page_expectations(array('has_custom_html_header' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_page_specific_html_header']); 
 }
 
 public function testGenerateData_NoCustomPageHTMLHeader()
 {
  $this->set_page_expectations(array('has_custom_html_header' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_page_specific_html_header']);
 }
 
 public function testGenerateData_HasCustomCSS()
 {
  $this->set_page_expectations(array('has_custom_css' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_page_specific_stylesheet']);
 }
 
 public function testGenerateData_NoCustomCSS()
 {
  $this->set_page_expectations(array('has_custom_css' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_page_specific_stylesheet']);
 }
 
 public function testGenerateData_HasCustomJS()
 {
  $this->set_page_expectations(array('has_custom_js' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_page_specific_javascript']);
 }
 
 public function testGenerateData_NoCustomJS()
 {
  $this->set_page_expectations(array('has_custom_js' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_page_specific_javascript']);
 }
 
 public function testGenerateData_NoContactForm()
 {
  $this->_config_expectations['walkercms.contact_page'] = '';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_contact_form']);
 }
 
 public function testGenerateData_HasContactForm()
 {
  $this->_config_expectations['walkercms.contact_page'] = 'home';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_contact_form']);
 }
 
 public function testGenerateData_ContactFormSet_NotSetToCurrentPage()
 {
  $this->_config_expectations['walkercms.contact_page'] = 'contact';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_contact_form']);
 }
 
 public function testGenerateData_ShowIEWarning()
 {
  $this->_config_expectations['walkercms.show_ie_warning'] = true;
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['show_ie_warning']);
 }
 
 public function testGenerateData_DoNotShowIEWarning()
 {
  $this->_config_expectations['walkercms.show_ie_warning'] = false;
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['show_ie_warning']);
 }
 
 public function testGenerateData_IEWarningMaximumUnsupportedVersion()
 {
  $this->_config_expectations['walkercms.maximum_unsupported_ie_version'] = '7';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('7', $result['maximum_unsupported_ie_version']);
 }
 
 public function testGenerateData_DifferentIEWarningMinimumVersion()
 {
  $this->_config_expectations['walkercms.maximum_unsupported_ie_version'] = '6';
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('6', $result['maximum_unsupported_ie_version']);
 }
 
 public function testGenerateData_HasSecondaryContent()
 {
  $this->set_page_expectations(array('has_secondary_content' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_secondary_content']);
 }
 
 public function testGenerateData_NoSecondaryContent()
 {
  $this->set_page_expectations(array('has_secondary_content' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_secondary_content']);
 }
 
 public function testGenerateData_HasCustomPageHeader()
 {
  $this->set_page_expectations(array('has_custom_page_header' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_page_specific_header']);
 }
 
 public function testGenerateData_NoCustomPageHeader()
 {
  $this->set_page_expectations(array('has_custom_page_header' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_page_specific_header']);
 }
 
 public function testGenerateData_CorrectContentSourcePageID()
 {
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('about', $result['content_page_id']);
 }
 
 public function testGenerateData_DifferentContentSourcePageID()
 {
  $this->_context->set_content_source_page($this->_current_page);
  $result = $this->_generator->generate_data($this->_context);
  $this->assertEquals('home', $result['content_page_id']);
 }
 
 public function testGenerateData_HasSubNav_HasRequiredSubNav()
 {
  $this->_required_determiner->expects($this->once())
                             ->method('is_required')
                             ->will($this->returnValue(true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_sub_nav']);
 }
 
 public function testGenerateData_HasSubNav_SubNavNotRequired()
 {
  $this->_required_determiner->expects($this->once())
                             ->method('is_required')
                             ->will($this->returnValue(false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_sub_nav']);
 }
 
 public function testGenerateData_HasCustomFooter()
 {
  $this->set_page_expectations(array('has_custom_footer' => true));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertTrue($result['has_page_specific_footer']);
 }
 
 public function testGenerateData_NoCustomFooter()
 {
  $this->set_page_expectations(array('has_custom_footer' => false));
  $result = $this->_generator->generate_data($this->_context);
  $this->assertFalse($result['has_page_specific_footer']);
 }
}

/* End of file templatedatagenerator.test.php */
/* Location: ./WalkerCMS/tests/templatedatagenerator.test.php */