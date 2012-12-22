<?php
require_once(path('app') . 'controllers/page.php');
require_once(path('app') . 'tests/stubs/pages_retriever.stub.php');
require_once(path('app') . 'tests/stubs/page_id_validator.stub.php');
require_once(path('app') . 'tests/stubs/template_data_generator.stub.php');
require_once(path('app') . 'tests/stubs/nav_data_generator.stub.php');
require_once(path('app') . 'tests/stubs/page_model.stub.php');

class TestPageController extends PHPUnit_Framework_TestCase
{
 private $_controller = null;
 private $_pages = null;
 private $_page_model_options = null;

 protected function setUp()
 {
  $this->_pages = array();
  $pages_ref =& $this->_pages;
  $this->_page_model_options = array();
  $page_model_options_ref =& $this->_page_model_options;

  IoC::register('page_model', function($page_id) use (&$page_model_options_ref)
  {
   return new PageModel_Stub($page_id, $page_model_options_ref);
  });

  IoC::register('pages_retriever', function() use (&$pages_ref)
  {
   return new PagesRetriever_Stub($this->_pages);
  });

  IoC::register('page_id_validator', function()
  {
   return new PageIDValidator_Stub('home');
  });

  $this->_controller = new Page_Controller(new PagesRetriever_Stub($this->_pages), 
                                           new PageIDValidator_Stub('home'),
                                           new TemplateDataGenerator_Stub(),
                                           new NavDataGenerator_Stub());
 }

 public function testSomethingIsTrue()
 {
 	//$this->assertTrue(true);
 }
}

/* End of file page_controller.test.php */
/* Location: ./WalkerCMS/tests/page_controller.test.php */