<?php
class PageIDValidatorTest extends PHPUnit_Framework_TestCase
{
 private $_logger = null;
 private $_validator = null;
 private $_pages = null;

 protected function setUp()
 {
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_validator = new PageIDValidator($this->_logger);
  $this->_pages = array(
    '404' => array(),
    'home' => array(),
    'other' => array(),
  );
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_validator->get_validated_page_id($this->_pages, '');
 }

 public function testEmptyPageID()
 {
  $this->performTests('home', '');
 }

 public function testPageIDInPages()
 {
  $this->performTests('other', 'other');
 }

 public function testWhiteSpaceOnly()
 {
  $this->performTests('home', '  ');
 }

 public function testPageIDInPages_PaddedWithWhitespace()
 {
  $this->performTests('other', ' other ');
 }

 public function testPageIDContainsInterstitialSpace()
 {
  $this->performInvalidCharacterTests('contains space');
 }

 public function testPageIDContainsInvalidCharacter()
 {
  for ($i = 0; $i < 256; $i++)
  {
   $letter = chr($i);
   if (preg_match('/^[a-zA-Z0-9_\.]+$/', $letter)) {
    continue;
   }
   $this->performInvalidCharacterTests("Contains_'$letter'");
  }
 }

 public function testPageIDDoesNotExist()
 {
  $this->performTests('404', 'about');
 }

 private function performInvalidCharacterTests($input)
 {
  $this->_pages[$input] = array();
  $this->performTests('404', $input);
 }

 private function performTests($expected_result, $input)
 {
  $this->assertEquals($expected_result, $this->_validator->get_validated_page_id($this->_pages, $input));
 }
}

/* End of file pageidvalidator.test.php */
/* Location: ./WalkerCMS/tests/pageidvalidator.test.php */