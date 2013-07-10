<?php
class FormGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_inner_generator = null;
 private $_view = null;
 private $_logger = null;
 private $_generator = null;
 private $_context = null;
 
 protected function setUp()
 {
  $this->_inner_generator = $this->getMock('IFormDataGenerator', array('generate'));
  $this->_view = $this->getMock('IViewAdapter', array('generate_view'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
  
  $this->_generator = new FormGenerator($this->_inner_generator, $this->_view, $this->_logger);
  
  $this->_context = new AppContext();
 }
 
 public function testLoggerInteraction()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_generator->generate('information', $this->_context);
 }
  
 public function testGenerate_DefaultFormResultView()
 {
  $generated_data = array('form' => array('id' => 'information'));
  $generated_form = array('generated' => true);
  $this->_inner_generator->expects($this->once())
                         ->method('generate')
                         ->with('information', $this->_context)
                         ->will($this->returnValue($generated_data));
  $this->_view->expects($this->once())
              ->method('generate_view')
              ->with('forms.main', $this->equalTo($generated_data))
              ->will($this->returnValue($generated_form));
  $this->assertSame($generated_form, $this->_generator->generate('information', $this->_context));
 }
 
 public function testGenerate_UseExistingFormResultView()
 {
  $this->_context->set_form_result_view('forms.success');
  $generated_data = array('form' => array('id' => 'information'));
  $generated_form = array('generated' => true);
  $this->_inner_generator->expects($this->once())
       ->method('generate')
       ->with('information', $this->_context)
       ->will($this->returnValue($generated_data));
  $this->_view->expects($this->once())
       ->method('generate_view')
       ->with('forms.success', $this->equalTo($generated_data))
       ->will($this->returnValue($generated_form));
  $this->assertSame($generated_form, $this->_generator->generate('information', $this->_context));
 }
 
 public function testGenerate_ClearFormResultView()
 {
  $this->_context->set_form_result_view('forms.success');
  $generated_data = array('form' => array('id' => 'information'));
  $generated_form = array('generated' => true);
  $this->_inner_generator->expects($this->once())
       ->method('generate')
       ->with('information', $this->_context)
       ->will($this->returnValue($generated_data));
  $this->_view->expects($this->once())
       ->method('generate_view')
       ->with('forms.success', $this->equalTo($generated_data))
       ->will($this->returnValue($generated_form));
  $result = $this->_generator->generate('information', $this->_context);
  
  $this->assertEquals('default', $this->_context->get_form_result_view('default'));
 }
 
 public function testGenerate_ClearFormResultView_DifferentGetDefault()
 {
  $this->_context->set_form_result_view('forms.success');
  $generated_data = array('form' => array('id' => 'information'));
  $generated_form = array('generated' => true);
  $this->_inner_generator->expects($this->once())
       ->method('generate')
       ->with('information', $this->_context)
       ->will($this->returnValue($generated_data));
  $this->_view->expects($this->once())
       ->method('generate_view')
       ->with('forms.success', $this->equalTo($generated_data))
       ->will($this->returnValue($generated_form));
  $result = $this->_generator->generate('information', $this->_context);
 
  $this->assertEquals('different', $this->_context->get_form_result_view('different'));
 }
}

/* End of file formgenerator.test.php */
/* Location: ./WalkerCMS/tests/formgenerator.test.php */