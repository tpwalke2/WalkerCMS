<?php
require_once(path('app') . 'controllers/page.php');
require_once(path('app') . 'helpers/interfaces/pages_retriever.php');
require_once(path('app') . 'helpers/page_id_validator.php');
require_once(path('app') . 'helpers/interfaces/data_generator.php');
require_once(path('app') . 'helpers/interfaces/page_retriever.php');
require_once(path('app') . 'helpers/interfaces/logger_adapter.php');
require_once(path('app') . 'models/page_model.php');

class TestPageController extends PHPUnit_Framework_TestCase
{
 private $_pages_retriever = null;
 private $_page_id_validator = null;
 private $_template_data_generator = null;
 private $_nav_data_generator = null;
 private $_sub_nav_data_generator = null;
 private $_content_source_page_retriever = null;
 private $_logger = null;
 private $_controller = null;
 private $_pages = null;
 private $_page_model_options = null;

 protected function setUp()
 {
  $this->_pages_retriever = $this->getMock('IPagesRetriever', array('get_pages'));
  $this->_page_id_validator = $this->getMock('PageIDValidator', array('get_validated_page_id'));
  $this->_template_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_sub_nav_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_content_source_page_retriever = $this->getMock('IPageRetriever', array('get_page'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  $this->_controller = new Page_Controller($this->_pages_retriever, 
                                           $this->_page_id_validator,
                                           $this->_template_data_generator,
                                           $this->_nav_data_generator,
                                           $this->_sub_nav_data_generator,
                                           $this->_content_source_page_retriever,
                                           $this->_logger);
 }

 public function testSomethingIsTrue()
 {
 	//$this->assertTrue(true);
 }
}

/* End of file page_controller.test.php */
/* Location: ./WalkerCMS/tests/page_controller.test.php */