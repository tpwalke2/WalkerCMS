<?php
class FormsConfigValidatorTest extends PHPUnit_Framework_TestCase
{
 private $_validator = null;
 private $_config = null;
 
 protected function setUp()
 {
  $this->_validator = new FormsConfigValidator();
  $this->_config = array('forms' => array());
 }
  
 public function testValidate_FormsNotSet()
 {
  $this->_config = array();
  
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_OneFormNoID()
 {
  $this->_config['forms']['info'] = array('description' => 'Information');
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Form with id \'info\' does not have the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoForms_OneHasNoID()
 {
  $this->_config['forms']['info'] = array(
    'id'          => 'info',
    'description' => 'Information',
  );
  $this->_config['forms']['contact'] = array('description' => 'Contact Us');
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Form with id \'contact\' does not have the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoForms_BothHaveNoID()
 {
  $this->_config['forms']['info'] = array('description' => 'Information');
  $this->_config['forms']['contact'] = array('description' => 'Contact Us');
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Form with id \'info\' does not have the \'id\' attribute.', $result['errors'][0]);
  $this->assertEquals('Form with id \'contact\' does not have the \'id\' attribute.', $result['errors'][1]);
 }
 
 public function testValidate_OneFormNoDescription()
 {
  $this->_config['forms']['info'] = array('id' => 'info');
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Form with id \'info\' does not have the \'description\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoForms_OneHasNoDescription()
 {
  $this->_config['forms']['info'] = array(
    'id'          => 'info',
    'description' => 'Information',
  );
  $this->_config['forms']['contact'] = array('id' => 'contact');
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Form with id \'contact\' does not have the \'description\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoForms_BothHaveNoDescription()
 {
  $this->_config['forms']['info'] = array('id' => 'info');
  $this->_config['forms']['contact'] = array('id' => 'contact');
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Form with id \'info\' does not have the \'description\' attribute.', $result['errors'][0]);
  $this->assertEquals('Form with id \'contact\' does not have the \'description\' attribute.', $result['errors'][1]);
 }
 
 public function testValidate_FormMayNotContainItemsDirectly()
 {
  $this->_config['forms']['info'] = array(
    'id'          => 'info',
    'description' => 'Information',
    'items'       => array(),
  );
  
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Items in form with id \'info\' must be placed in sections.', $result['errors'][0]);
 }
 
 public function testValidate_OneSection_HasNoID()
 {
  $this->_config['forms']['info'] = array(
    'id'          => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'description' => 'Basic Information',
        'show_label'  => true,
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Section at index \'0\' in form \'info\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoSections_OneHasNoID()
 {
  $this->_config['forms']['contact'] = array(
    'id'          => 'contact',
    'description' => 'Contact',
    'sections'       => array(
      array(
        'id'          => 'info',
        'description' => 'Basic Information',
        'show_label'  => true,
      ),
      array(
        'description' => 'Contact Information',
        'show_label'  => true,
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Section at index \'1\' in form \'contact\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_OneSection_HasNoDescription()
 {
  $this->_config['forms']['info'] = array(
    'id'          => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'show_label'  => true,
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Section at index \'0\' in form \'info\' is missing the \'description\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_OneSection_HasNoShowLabel()
 {
  $this->_config['forms']['info'] = array(
    'id'          => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Section at index \'0\' in form \'info\' is missing the \'show_label\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_ValidTextAndSectionType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ValidEmailType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'email',
            'description' => 'Email',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'email',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ValidPhoneType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'homephone',
            'description' => 'Home Phone',
            'maxlength'   => 20,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'phone',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ValidRepeatingGroupType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'otherinfo',
            'description' => 'Other Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ValidDateSelectType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'dob',
            'description' => 'DOB',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'date_select',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ValidMultipleChoiceType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'select',
            'description' => 'Select Something',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'multiple_choice',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_InvalidType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'asdf',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' has an invalid type of \'asdf\'.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithOneValidItem()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ItemIDIsAllValidCharacters()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_ItemIDContainsInvalidCharacters()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'Item_`!@#$%^&*()-+=[]\\{}|;:\'",./<>?',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' has an invalid \'id\' attribute. IDs may only contain alphanumeric characters.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithOneItem_HasNoID()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithTwoItems_OneHasNoID()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          ),
          array(
            'description' => 'Last Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
   
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in section \'basic_info\' of form \'info\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoSections_OneHasItemWithNoID()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          ),
         )
        ),
      array(
        'id'          => 'preferences',
        'description' => 'Contact Preferences',
        'show_label'  => true,
        'items'       => array(
          array(
            'description' => 'Time to Call',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
   
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'preferences\' of form \'info\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithOneItem_HasNoDescription()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' is missing the \'description\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithOneItem_HasNoShowLabel()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'required'    => false,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' is missing the \'show_label\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_OneItem_HasNoType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'required'    => false,
            'show_label'  => true,
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' is missing the \'type\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithOneItem_HasNoRequired()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'basic_info\' of form \'info\' is missing the \'required\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_SectionWithTwoItems_OneHasNoRequired()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          ),
          array(
            'id'          => 'lname',
            'description' => 'Last Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
   
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in section \'basic_info\' of form \'info\' is missing the \'required\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_TwoSections_OneHasItemWithNoRequired()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'fname',
            'description' => 'First Name',
            'maxlength'   => 80,
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
          ),
        )
      ),
      array(
        'id'          => 'preferences',
        'description' => 'Contact Preferences',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'time_to_call',
            'description' => 'Time to Call',
            'show_label'  => true,
            'type'        => 'text',
          )
        ),
      )
    ),
  );
   
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in section \'preferences\' of form \'info\' is missing the \'required\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithOneValidItem()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
 
 public function testValidate_RepeatingGroupWithOneItem_HasNoID()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithTwoItems_HasNoID()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              ),
              array(
                'description' => 'Dates Worked',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'id\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithOneItem_HasNoDescription()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'description\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithTwoItems_HasNoDescription()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              ),
              array(
                'id'          => 'dates_worked',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'description\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithOneItem_HasNoShowLabel()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'show_label\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithTwoItems_HasNoShowLabel()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              ),
              array(
                'id'          => 'dates_worked',
                'description' => 'Dates Worked',
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'show_label\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithOneItem_HasNoRequired()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'required\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithTwoItems_HasNoRequired()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              ),
              array(
                'id'          => 'dates_worked',
                'description' => 'Dates Worked',
                'show_label'  => true,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'required\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithOneItem_HasNoType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'0\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'type\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupWithTwoItems_HasNoType()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'employer',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              ),
              array(
                'id'          => 'dates_worked',
                'description' => 'Dates Worked',
                'show_label'  => true,
                'required'    => false,
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Item at index \'1\' in repeating_group item \'additional\' in section \'basic_info\' of form \'info\' is missing the \'type\' attribute.', $result['errors'][0]);
 }
 
 public function testValidate_RepeatingGroupItemsMayNotHaveRepeatingGroupItems()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'repeating_group',
            'items'       => array(
              array(
                'id'          => 'something_else',
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'repeating_group',
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertFalse($result['valid']);
  $this->assertEquals('Repeating group item at index \'0\' in section \'basic_info\' of form \'info\' must not have items of type \'repeating_group\'.', $result['errors'][0]);
 }
 
 public function testValidate_NonRepeatingGroupTypeSubItemHasItems_DoNotValidate()
 {
  $this->_config['forms']['info'] = array(
    'id' => 'info',
    'description' => 'Information',
    'sections'       => array(
      array(
        'id'          => 'basic_info',
        'description' => 'Basic Information',
        'show_label'  => true,
        'items'       => array(
          array(
            'id'          => 'additional',
            'description' => 'Additional Info',
            'show_label'  => true,
            'required'    => false,
            'type'        => 'text',
            'items'       => array(
              array(
                'description' => 'Employer',
                'show_label'  => true,
                'required'    => false,
                'type'        => 'text',
                'maxlength'   => 50,
              )
            ),
          )
        ),
      )
    ),
  );
 
  $result = $this->_validator->validate($this->_config);
  $this->assertTrue($result['valid']);
  $this->assertEquals(0, count($result['errors']));
 }
}
/* End of file formsconfigvalidator.test.php */
/* Location: ./WalkerCMS/tests/formsconfigvalidator.test.php */