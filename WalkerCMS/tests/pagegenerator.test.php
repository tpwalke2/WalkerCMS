<?php
class PageGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_template_data_generator = null;
 private $_html_header_data_generator = null;
 private $_page_header_data_generator = null;
 private $_content_data_generator = null;
 private $_nav_data_generator = null;
 private $_sub_nav_data_generator = null;
 private $_custom_sub_nav_data_generator = null;
 private $_secondary_content_data_generator = null;
 private $_footer_data_generator = null;
 private $_content_source_page_retriever = null;
 private $_logger = null;
 
 private $_generator = null;
 private $_pages = null;
 private $_current_page = null;
 
 protected function setUp()
 {
  $this->_template_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_html_header_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_page_header_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_content_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_sub_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_custom_sub_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_secondary_content_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_footer_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_content_source_page_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_generator = new PageGenerator( $this->_template_data_generator,
                                           $this->_html_header_data_generator,
                                           $this->_page_header_data_generator,
                                           $this->_content_data_generator,
                                           $this->_nav_data_generator,
                                           $this->_sub_nav_data_generator,
                                           $this->_custom_sub_nav_data_generator,
                                           $this->_secondary_content_data_generator,
                                           $this->_footer_data_generator,
                                           $this->_content_source_page_retriever,
                                           $this->_logger);
 }
 
 public function testSomething()
 {
  $this->assertTrue(true);
 }
}

/* End of file pagegenerator.test.php */
/* Location: ./WalkerCMS/tests/pagegenerator.test.php */