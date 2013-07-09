<?php
class FormDataGeneratorTest extends PHPUnit_Framework_TestCase
{
 private $_config = null;
 private $_logger = null;
 private $_generator = null;
 private $_forms_list = null;
 private $_context = null;

 protected function setUp()
 {
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));

  $this->_generator = new FormDataGenerator($this->_config, $this->_logger);
  
  $info_form = array('id' => 'information', 'indexed_items' => array());
  $contact_form = array('id' => 'contact', 'indexed_items' => array());
  $this->_forms_list = array('information' => $info_form, 'contact' => $contact_form);
  $this->_context = new AppContext();
 }
 
 public function testLoggerInteraction()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_generator->generate('information', $this->_context);
 }

 public function testGenerate_GetCorrectForm()
 {
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.forms')
                ->will($this->returnValue($this->_forms_list));
 
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertSame($this->_forms_list['information'], $result_data['form']);
 }
 
 public function testGenerate_SetRequestingPageID()
 {
  $this->_context->set_current_page_id('home');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.forms')
                ->will($this->returnValue($this->_forms_list));
 
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals('home', $result_data['page_id']);
 }
 
 public function testGenerate_SetDifferentRequestingPageID()
 {
  $this->_context->set_current_page_id('contact');
  $this->_config->expects($this->once())
                ->method('get')
                ->with('walkercms.forms')
                ->will($this->returnValue($this->_forms_list));
 
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals('contact', $result_data['page_id']);
 }
 
 public function testGenerate_GetMonthNames()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals(12, count($result_data['full_month_names']));
  $this->assertEquals('January', $result_data['full_month_names'][0]);
  $this->assertEquals('February', $result_data['full_month_names'][1]);
  $this->assertEquals('March', $result_data['full_month_names'][2]);
  $this->assertEquals('April', $result_data['full_month_names'][3]);
  $this->assertEquals('May', $result_data['full_month_names'][4]);
  $this->assertEquals('June', $result_data['full_month_names'][5]);
  $this->assertEquals('July', $result_data['full_month_names'][6]);
  $this->assertEquals('August', $result_data['full_month_names'][7]);
  $this->assertEquals('September', $result_data['full_month_names'][8]);
  $this->assertEquals('October', $result_data['full_month_names'][9]);
  $this->assertEquals('November', $result_data['full_month_names'][10]);
  $this->assertEquals('December', $result_data['full_month_names'][11]);
 }
 
 public function testGenerate_GetDateNumbers()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals(31, count($result_data['dates']));
  for ($i = 1; $i <= 31; $i++)
  {
   $this->assertEquals($i, $result_data['dates'][$i]);
  }
 }
 
 public function testGenerate_GetYears()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $result_data = $this->_generator->generate('information', $this->_context);
  $current_year = date('Y');
  $total_years = $current_year - 1900 + 1;
  $this->assertEquals($total_years, count($result_data['years']));
  for ($i = 0; $i < $total_years; $i++)
  {
   $this->assertEquals($current_year - $i, $result_data['years'][$current_year - $i]);
  }
 }
 
 public function testGenerate_NoExistingFormData_StillContainsItemErrors()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $this->_context->clear_form_data();
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals(0, count($result_data['item_errors']));
 }
 
 public function testGenerate_HasExistingFormData_MergeErrors()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $form_data = array('item_errors' => array(''));
  $form_data['item_errors']['data_item1'] = 'item1 value';
  $form_data['item_errors']['data_item2'] = 'item2 value';
  $this->_context->set_form_data($form_data);
  $item1 = array('id' => 'item1', 'input_name' => 'data_item1');
  $item2 = array('id' => 'item2', 'input_name' => 'data_item2');
  $this->_forms_list['information']['indexed_items']['key1'] = $item1;
  $this->_forms_list['information']['indexed_items']['key2'] = $item2;
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertNull($this->_context->get_form_data());
 }
 
 public function testGenerate_NoExistingFormData_StillContainsItemValues()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $this->_context->clear_form_data();
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals(0, count($result_data['item_values']));
 }
 
 public function testGenerate_NoExistingFormData_FormItemsInitializedToEmpty()
 {
  $this->_context->clear_form_data();
  $item1 = array('id' => 'item1', 'input_name' => 'data_item1');
  $item2 = array('id' => 'item2', 'input_name' => 'data_item2');
  $this->_forms_list['information']['indexed_items']['key1'] = $item1;
  $this->_forms_list['information']['indexed_items']['key2'] = $item2;
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals(2, count($result_data['item_values']));
  $this->assertEquals('', $result_data['item_values']['data_item1']);
  $this->assertEquals('', $result_data['item_values']['data_item2']);
 }
 
 public function testGenerate_HasExistingFormData_FormItemsContainInputValues()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $form_data = array('item_values' => array());
  $form_data['item_values']['data_item1'] = 'item1 value';
  $form_data['item_values']['data_item2'] = 'item2 value';
  $this->_context->set_form_data($form_data);
  $item1 = array('id' => 'item1', 'input_name' => 'data_item1');
  $item2 = array('id' => 'item2', 'input_name' => 'data_item2');
  $this->_forms_list['information']['indexed_items']['key1'] = $item1;
  $this->_forms_list['information']['indexed_items']['key2'] = $item2;
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertEquals(2, count($result_data['item_values']));
  $this->assertEquals('item1 value', $result_data['item_values']['data_item1']);
  $this->assertEquals('item2 value', $result_data['item_values']['data_item2']);
 }
 
 public function testGenerate_HasExistingFormData_ClearFormDataAfterRetrieving()
 {
  $this->_config->expects($this->once())
       ->method('get')
       ->with('walkercms.forms')
       ->will($this->returnValue($this->_forms_list));
  $form_data = array('item_values' => array());
  $form_data['item_values']['data_item1'] = 'item1 value';
  $form_data['item_values']['data_item2'] = 'item2 value';
  $this->_context->set_form_data($form_data);
  $item1 = array('id' => 'item1', 'input_name' => 'data_item1');
  $item2 = array('id' => 'item2', 'input_name' => 'data_item2');
  $this->_forms_list['information']['indexed_items']['key1'] = $item1;
  $this->_forms_list['information']['indexed_items']['key2'] = $item2;
  $result_data = $this->_generator->generate('information', $this->_context);
  $this->assertNull($this->_context->get_form_data());
 }
}

/* End of file formdatagenerator.test.php */
/* Location: ./WalkerCMS/tests/formdatagenerator.test.php */