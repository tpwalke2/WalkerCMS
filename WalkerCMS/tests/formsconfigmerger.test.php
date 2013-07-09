<?php
// TODO: merge in default form item values based on type
class FormsConfigMergerTest extends PHPUnit_Framework_TestCase
{
 private $_merger = null;
 
 protected function setUp()
 {
  $this->_merger = new FormsConfigMerger();
 }
 
 public function testMerge_NothingToMerge()
 {
  $config = array('version' => '0.9');
  $forms = array();
  $form_item_defaults = array();
  
  $this->assertEquals($config, $this->_merger->merge($forms, $form_item_defaults, $config));
 }
 
 public function testMerge_OneFormGetsAddedToConfig()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array('description' => 'Information'));
  $form_item_defaults = array();
  
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals(1, count($result['forms']));
 } 
 
 public function testMerge_PushIDIntoForm()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array('description' => 'Information'));
  $form_item_defaults = array();
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('info', $result['forms']['info']['id']);
 }
 
 public function testMerge_PushIDIntoFormEvenIfDefinedAlready()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('contact' => array(
    'id'          => 'info',
    'description' => 'Contact Us'
  ));
  $form_item_defaults = array();
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('contact', $result['forms']['contact']['id']);
 }
 
 public function testMerge_OneForm_ReferencedByHashKey()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array('description' => 'Information'));
  $form_item_defaults = array();
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertTrue(isset($result['forms']['info']));
 }
 
 public function testMerge_OneForm_CopyOneAttribute()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array('description' => 'Information'));
  $form_item_defaults = array();
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('Information', $result['forms']['info']['description']);
 }
 
 public function testMerge_OneForm_CopyAllAttributes()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'id'    => 'info',
    'other' => true,
    ));
  $form_item_defaults = array();
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('info', $result['forms']['info']['id']);
  $this->assertTrue($result['forms']['info']['other']);
 }
 
 public function testMerge_TwoFormsAddedToConfig()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array(
    'info'    => array('description' => 'Information'),
    'contact' => array('description' => 'Contact Us')
  );
  $form_item_defaults = array();
  
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals(2, count($result['forms']));
 }
 
 public function testMerge_MergeDefaultsWithOneSection()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array('description' => 'Basic Information'),
    )));
  $form_item_defaults = array(
    'type' => 'text',
    'show_label' => true,
  );
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertTrue($result['forms']['info']['sections'][0]['show_label']);
 }
 
 public function testMerge_UserSectionsOverwriteDefaults()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array('show_label' => false)
    )
  ));
  $form_item_defaults = array(
    'type' => 'text',
    'show_label' => true,
  );
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertFalse($result['forms']['info']['sections'][0]['show_label']);
 }
 
 public function testMerge_RemoveNonAlphaNumericCharactersFromSectionID()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array('id' => 'basic_info'),
    )
  ));
  $form_item_defaults = array(
    'type' => 'text',
    'show_label' => true,
  );
  
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('basicinfo', $result['forms']['info']['sections'][0]['id']);
 }
 
 public function testMerge_LowerCaseType_UserInput()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections'    => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'fname',
            'type' => 'MULTI-CHOICE',
          ),
         ),
      )
     )
    )
  );
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('multi-choice', $result['forms']['info']['sections'][0]['items'][0]['type']);
 }
 
 public function testMerge_LowerCaseDefaultType()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array('id' => 'fname'),
        )
      ),
    ),
  ));
  $form_item_defaults = array('type' => 'TEXT');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('text', $result['forms']['info']['sections'][0]['items'][0]['type']);
 }
 
 public function testMerge_MergeDefaultsWithOneItem()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections'    => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array('id' => 'fname'),
        )
      ),
    )));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('text', $result['forms']['info']['sections'][0]['items'][0]['type']);
 }
 
 public function testMerge_RemoveNonAlphaNumericCharactersFromItemIDs()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections'    => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array('id' => 'f_name'),
        )
      ),
    )));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('fname', $result['forms']['info']['sections'][0]['items'][0]['id']);
 }
 
 public function testMerge_UserItemsOverwriteDefaults()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'fname',
            'type' => 'multi-select'
          ),
        )
      )
    )
   ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('multi-select', $result['forms']['info']['sections'][0]['items'][0]['type']);
 }
 
 public function testMerge_AddFullyQualifiedID()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'fname',
          ),
        )
      )
    )
  ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('basicinfo_fname', $result['forms']['info']['sections'][0]['items'][0]['fully_qualified_id']);
 }
 
 public function testMerge_AddFullDataInputName()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'fname',
          ),
        )
      )
    )
  ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('data_basicinfo_fname', $result['forms']['info']['sections'][0]['items'][0]['input_name']);
 }
 
 public function testMerge_RepeatingGroupItemIDsGetPrefixOfParentItem()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'additional',
            'type' => 'repeating_group',
            'items' => array(
              array('id' => 'employer')
            )
          ),
        )
      )
    )
  ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('additional_employer', $result['forms']['info']['sections'][0]['items'][0]['items'][0]['id']);
 }
 
 public function testMerge_RepeatingGroupItemsGetItemDefaults()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'additional',
            'type' => 'repeating_group',
            'items' => array(
              array('id' => 'employer')
            )
          ),
        )
      )
    )
  ));
  $form_item_defaults = array('type' => 'text');
  
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals('text', $result['forms']['info']['sections'][0]['items'][0]['items'][0]['type']);
 }
 
 public function testMerge_DoNotMergeDefaultsIfRepeatingGroupHasNoItems()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'additional',
            'type' => 'repeating_group',
            'items' => array()
          ),
        )
      )
    )
  ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals(0, count($result['forms']['info']['sections'][0]['items'][0]['items']));
 }
 
 public function testMerge_DoNotMergeItemDefaultsForNonRepeatingGroupTypes()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array(
            'id' => 'additional',
            'type' => 'multi-select',
            'items' => array(
              array('id' => 'employer')
            )
          ),
        )
      )
    )
  ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertFalse(isset($result['forms']['info']['sections'][0]['items'][0]['items'][0]['type']));
 }
 
 public function testMerge_IndexByFinalID()
 {
  $config = array(
    'version' => '0.9',
  );
  $forms = array('info' => array(
    'description' => 'Information',
    'sections' => array(
      array(
        'id' => 'basic_info',
        'items' => array(
          array('id' => 'fname'),
        )
      ),
      array(
        'id' => 'address_info',
        'items' => array(
          array('id' => 'address1'),
        )
      ),
    ),
  ));
  $form_item_defaults = array('type' => 'text');
 
  $result = $this->_merger->merge($forms, $form_item_defaults, $config);
  $this->assertEquals(2, count($result['forms']['info']['indexed_items']));
  $this->assertSame($result['forms']['info']['sections'][0]['items'][0], $result['forms']['info']['indexed_items']['basicinfo.fname']);
  $this->assertSame($result['forms']['info']['sections'][1]['items'][0], $result['forms']['info']['indexed_items']['addressinfo.address1']);
 }
}

/* End of file formsconfigmerger.test.php */
/* Location: ./WalkerCMS/tests/formsconfigmerger.test.php */