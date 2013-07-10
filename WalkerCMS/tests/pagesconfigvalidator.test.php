<?php
class PagesConfigValidatorTest extends PHPUnit_Framework_TestCase
{
 private $_validator = null;
 private $_config = null;

 protected function setUp()
 {
  $this->_validator = new PagesConfigValidator();
  $this->_config = array('pages' => array());
 }
 
 public function testValidate_PageIDIsReservedID_Site()
 {
  $this->_config['pages']['site'] = array('id' => 'site');
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('The page ID \'site\' is reserved.', $result['errors'][0]);
 }
 
 public function testValidate_PageIDIsReservedID_SiteMap()
 {
  $this->_config['pages']['sitemap'] = array('id' => 'sitemap');
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('The page ID \'sitemap\' is reserved.', $result['errors'][0]);
 }
 
 public function testValidate_NoPages()
 {
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_NoReservedWordID()
 {
  $this->_config['pages']['home'] = array('id' => 'home');
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
}
/* End of file pagesconfigvalidator.test.php */
/* Location: ./WalkerCMS/tests/pagesconfigvalidator.test.php */