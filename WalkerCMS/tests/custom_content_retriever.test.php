<?php
require_once(path('app') . 'helpers/custom_content_retriever.php');
require_once(path('app') . 'helpers/interfaces/data_generator.php');
require_once(path('app') . 'helpers/interfaces/view_adapter.php');

class TestCustomContentRetriever extends PHPUnit_Framework_TestCase
{
 private $_data_generator = null;
 private $_view_adapter = null;
 private $_content_retriever = null;
 
 protected function setUp()
 {
  $this->_data_generator = $this->getMock('IDataGenerator', array('generate_data'));
  $this->_view_adapter = $this->getMock('IViewAdapter', array('generate_view'));
  $this->_content_retriever = new CustomContentRetriever($this->_data_generator, $this->_view_adapter);
 }
 
 public function testRetrieveContent()
 {
  $data = array();
  $page = array('id' => 'home');
  $pages = array('home' => $page);
  $this->_data_generator->expects($this->once())
                        ->method('generate_data')
                        ->with($this->equalTo($pages), $this->equalTo($page))
                        ->will($this->returnValue($data));
  $this->_view_adapter->expects($this->once())
                      ->method('generate_view')
                      ->with('partials.page_inclusion', $this->equalTo($data))
                      ->will($this->returnValue('Generated page'));
  $this->assertEquals('Generated page', $this->_content_retriever->retrieve_content($pages, $page));
 }
}

/* End of file custom_content_retriever.test.php */
/* Location: ./WalkerCMS/tests/custom_content_retriever.test.php */