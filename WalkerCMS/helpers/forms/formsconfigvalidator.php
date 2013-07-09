<?php
class FormsConfigValidator implements IConfigValidator
{
 private $_valid_types = array('text', 'email', 'date_select', 'multiple_choice', 'phone', 'repeating_group');
 
 public function validate($config)
 {
  $valid = true;
  $errors = array();
  
  if (!isset($config['forms'])) { return compact('valid', 'errors'); }
  
  foreach ($config['forms'] as $formID=>$form)
  {
   foreach (array('id', 'description') as $required_attribute)
   {
    if (!isset($form[$required_attribute]))
    {
     $valid = false;
     $errors[] = "Form with id '$formID' does not have the '$required_attribute' attribute.";
    }
   }
   
   if (isset($form['items'])) 
   {
    $valid = false;
    $errors[] = "Items in form with id '$formID' must be placed in sections.";
   }
   
   if (isset($form['sections']))
   {
    $items_validation_result = $this->validate_sections($form['sections'], $form['id']);
    if (!$items_validation_result['valid']) { $valid = false; }
    foreach ($items_validation_result['errors'] as $error_msg)
    {
     $errors[] = $error_msg;
    }
   }
  }
  
  return compact('valid', 'errors');
 }
 
 private function validate_sections($sections, $formID)
 {
  $valid = true;
  $errors = array();
 
  foreach ($sections as $index=>$section)
  {
   foreach (array('id', 'description', 'show_label') as $required_attribute)
   {
    if (!isset($section[$required_attribute]))
    {
     $valid = false;
     $errors[] = "Section at index '$index' in form '$formID' is missing the '$required_attribute' attribute.";
    }
   }
    
   if (isset($section['items']))
   {
    $items_validation_result = $this->validate_items($section['items'], $formID, $section['id']);
    if (!$items_validation_result['valid'])
    {
     $valid = false;
     foreach ($items_validation_result['errors'] as $error)
     {
      $errors[] = $error;
     }
    }
   }
  }
   
  return compact('valid', 'errors');
 }
 
 private function validate_items($items, $formID, $sectionID, $repeating_group_items_allowed = true, $separator = '')
 {
  $valid = true;
  $errors = array();
  
  foreach ($items as $index=>$item)
  {
   foreach (array('id', 'description', 'show_label', 'type', 'required') as $required_attribute)
   {
    if (!isset($item[$required_attribute]))
    {
     $valid = false;
     $errors[] = "Item at index '$index' in$separator section '$sectionID' of form '$formID' is missing the '$required_attribute' attribute.";
    }
   }
   
   if (isset($item['type']))
   {
    if (!in_array($item['type'], $this->_valid_types))
    {
     $valid = false;
     $errors[] = "Item at index '$index' in section '$sectionID' of form '$formID' has an invalid type of '{$item['type']}'.";
    }
    
    if ($item['type'] == 'repeating_group')
    {
     if (!$repeating_group_items_allowed)
     {
      $valid = false;
      $errors[] = "Repeating group item at index '$index' in section '$sectionID' of form '$formID' must not have items of type 'repeating_group'.";
     } elseif (isset($item['items']))
     {
      $items_validation_result = $this->validate_items($item['items'], $formID, $sectionID, false, " repeating_group item '{$item['id']}' in");
      if (!$items_validation_result['valid'])
      {
       $valid = false;
       foreach ($items_validation_result['errors'] as $error)
       {
        $errors[] = $error;
       }
      }
     }
    }
   }
  }
  
  if (isset($item['id']))
  {
   if (!preg_match('/^[A-Za-z0-9]+$/', $item['id']))
   {
    $valid = false;
    $errors[] = "Item at index '$index' in$separator section '$sectionID' of form '$formID' has an invalid 'id' attribute. IDs may only contain alphanumeric characters.";
   }
  }
   
   return compact('valid', 'errors');
 }
}

/* End of file formsconfigvalidator.php */
/* Location: ./WalkerCMS/helpers/forms/formsconfigvalidator.php */