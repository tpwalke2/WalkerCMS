<?php
class FormValidatorRetrieverTest extends PHPUnit_Framework_TestCase
{
 private $_rules_generator1 = null;
 private $_rules_generator2 = null;
 private $_config = null;
 private $_validator = null;
 private $_input = null;
 private $_logger = null;
 private $_retriever = null;
 private $_config_expectations = null;
 private $_input_expectations = null;
 
 protected function setUp()
 {
  $this->_rules_generator1 = $this->getMock('IRulesGenerator', array('get_rules'));
  $this->_rules_generator2 = $this->getMock('IRulesGenerator', array('get_rules'));
  $this->_config = $this->getMock('IConfigAdapter', array('get', 'set'));
  $this->_config->expects($this->any())
       ->method('get')
       ->will($this->returnCallback(array($this, 'config_get_callback')));
  $this->_validator = $this->getMock('IValidatorAdapter', array('create_validator'));
  $this->_input = $this->getMock('IInputAdapter', array('all', 'get'));
  $this->_input->expects($this->any())
       ->method('get')
       ->will($this->returnCallback(array($this, 'input_get_callback')));
  $this->_input->expects($this->any())
       ->method('all')
       ->will($this->returnCallback(array($this, 'input_all_callback')));
  $this->_logger = $this->getMock('ILoggerAdapter', array('debug', 'error'));
 }
 
 public function config_get_callback($key, $default = null)
 {
  if (isset($this->_config_expectations[$key])) { return $this->_config_expectations[$key]; }
  return $default;
 }
 
 public function input_get_callback($key = null, $default = null)
 {
  $this->assertNotNull($key);
  if (isset($this->_input_expectations[$key])) { return $this->_input_expectations[$key]; }
  return $default;
 }
 
 public function input_all_callback()
 {
  return $this->_input_expectations;
 }
 
 public function testGetFormValidator_UseLogger()
 {
  $this->_logger->expects($this->atLeastOnce())->method('debug');
  $this->_retriever = new FormValidatorRetriever(array('text' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
  
  $this->_retriever->get_form_validator();
 }
 
 public function testGetFormValidator_FormIDNotSet()
 {
  unset($this->_input_expectations['form_id']);
  $this->_retriever = new FormValidatorRetriever(array('text' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
  
  $this->assertNull($this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_FormIDEmpty()
 {
  $this->_input_expectations['form_id'] = '';
  $this->_retriever = new FormValidatorRetriever(array('text' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
 
  $this->assertNull($this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_FormIDAllSpaces()
 {
  $this->_input_expectations['form_id'] = '  ';
  $this->_retriever = new FormValidatorRetriever(array('text' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
 
  $this->assertNull($this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_FormNotAvailable()
 {
  $this->_config_expectations['forms'] = array(
    'information' => array(),
  );
  $this->_input_expectations['form_id'] = 'unavailable';
  $this->_retriever = new FormValidatorRetriever(array('text' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
 
  $this->assertNull($this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_ItemsAvailable_GeneratorNotAvailableForTypes()
 {
  $this->_config_expectations['walkercms.forms'] = array(
    'information' => array(
      'indexed_items' => array(
        'section_item1' => array(
          'id' => 'item1',
          'type' => 'email'),
      )
    ),
  );
  $this->_input_expectations['form_id'] = 'information';
  $this->_validator->expects($this->never())
                   ->method('create_validator');
  $this->_rules_generator1->expects($this->never())
                          ->method('get_rules');
  $this->_retriever = new FormValidatorRetriever(array('text' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
  $this->assertNull($this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_ItemsAvailable_GeneratorAvailableForType()
 {
  $item1 = array(
    'id' => 'item1',
    'type' => 'email'
  );
  $this->_config_expectations['walkercms.forms'] = array(
    'information' => array(
      'indexed_items' => array(
        'section_item1' => $item1,
      )
    ),
  );
  $this->_input_expectations['form_id'] = 'information';
  $this->_rules_generator1->expects($this->once())
                          ->method('get_rules')
                          ->with($this->equalTo($item1))
                          ->will($this->returnValue('item1 rules'));
  $expected_rules = array('data_section_item1' => 'item1 rules');
  $generated_validator = array('validators');
  
  $this->_validator->expects($this->once())
                   ->method('create_validator')
                   ->with($this->equalTo($this->_input_expectations), $this->equalTo($expected_rules))
                   ->will($this->returnValue($generated_validator));
  $this->_retriever = new FormValidatorRetriever(array('email' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
  $this->assertEquals($generated_validator, $this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_OneGeneratorReturnsEmpty()
 {
  $item1 = array(
    'id' => 'item1',
    'type' => 'phone'
  );
  $item2 = array(
    'id' => 'item2',
    'type' => 'email'
  );
  $this->_config_expectations['walkercms.forms'] = array(
    'information' => array(
      'indexed_items' => array(
        'section_item1' => $item1,
        'section_item2' => $item2,
      )
    ),
  );
  $this->_input_expectations['form_id'] = 'information';
  $this->_rules_generator1->expects($this->once())
                          ->method('get_rules')
                          ->with($this->equalTo($item2))
                          ->will($this->returnValue('item2 rules'));
  $this->_rules_generator2->expects($this->once())
                          ->method('get_rules')
                          ->with($this->equalTo($item1))
                          ->will($this->returnValue(''));
  $expected_rules = array('data_section_item2' => 'item2 rules');
  $generated_validator = array('validators');
 
  $this->_validator->expects($this->once())
                   ->method('create_validator')
                   ->with($this->equalTo($this->_input_expectations), $this->equalTo($expected_rules))
                   ->will($this->returnValue($generated_validator));
  $this->_retriever = new FormValidatorRetriever(array('email' => $this->_rules_generator1, 'phone' => $this->_rules_generator2), $this->_config, $this->_validator, $this->_input, $this->_logger);
  $this->assertEquals($generated_validator, $this->_retriever->get_form_validator());
 }
 
 public function testGetFormValidator_GeneratorReturnsAllSpaces()
 {
  $item1 = array(
    'id' => 'item1',
    'type' => 'email'
  );
  $this->_config_expectations['walkercms.forms'] = array(
    'information' => array(
      'indexed_items' => array(
        'section_item1' => $item1,
      )
    ),
  );
  $this->_input_expectations['form_id'] = 'information';
  $this->_rules_generator1->expects($this->once())
                          ->method('get_rules')
                          ->with($this->equalTo($item1))
                          ->will($this->returnValue('  '));
  $generated_validator = array('validators');
 
  $this->_validator->expects($this->never())
                   ->method('create_validator');
  $this->_retriever = new FormValidatorRetriever(array('email' => $this->_rules_generator1), $this->_config, $this->_validator, $this->_input, $this->_logger);
  $this->assertNull($this->_retriever->get_form_validator());
 }
}

/* End of file formvalidatorretriever.test.php */
/* Location: ./WalkerCMS/tests/formvalidatorretriever.test.php */