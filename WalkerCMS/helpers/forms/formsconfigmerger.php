<?php
class FormsConfigMerger implements IConfigMerger
{
 public function merge($forms, $form_item_defaults, $config)
 {
  if (count($forms) == 0) { return $config; }
  
  $config['forms'] = array();
  
  foreach ($forms as $formID=>$form)
  {
   $form['id'] = $formID;
   $form['indexed_items'] = array();
   $config['forms'][$formID] = $form;
   
   if (!isset($form['sections'])) { continue; }
  
   foreach ($form['sections'] as $section_index=>$section)
   {
    foreach ($form_item_defaults as $id=>$value)
    {
     if (isset($config['forms'][$formID]['sections'][$section_index][$id])) { continue; }
     $config['forms'][$formID]['sections'][$section_index][$id] = $value;
    }
    
    if (isset($section['id']))
    {
     $section['id'] = preg_replace('/[^a-zA-Z0-9]*/', '', $section['id']);
     $config['forms'][$formID]['sections'][$section_index]['id'] = $section['id'];
    }
    
    if (!isset($section['items'])) { continue; }
    
    $config['forms'][$formID]['sections'][$section_index]['items'] = $this->mergeItemDefaults($section['items'], $form_item_defaults, $section['id'], $config['forms'][$formID]['indexed_items']);
   }
  }
  
  return $config;
 }
 
 private function mergeItemDefaults($items, $item_defaults, $section_id, &$indexed_items, $item_id_prefix = '')
 {
  $result = array();
  
  foreach ($items as $item_index=>$item)
  {
   $item['id'] = preg_replace('/[^a-zA-Z0-9]*/', '', $item['id']);
   
   if ($item_id_prefix != '')
   {
    $item['id'] = $item_id_prefix . '_' . $item['id'];
   }
   
   $item['fully_qualified_id'] = $section_id . '_' . $item['id'];
   $item['input_name'] = 'data_' . $item['fully_qualified_id'];
   
   $result[$item_index] = $item;
   
   foreach ($item_defaults as $default_id=>$default_value)
   {
    if (isset($result[$item_index][$default_id])) { continue; }
    $result[$item_index][$default_id] = $default_value;
   }
   
   if (isset($result[$item_index]['type']))
   {
    $result[$item_index]['type'] = strtolower($result[$item_index]['type']);
     
    if (($result[$item_index]['type'] == 'repeating_group') && isset($result[$item_index]['items']))
    {
     $result[$item_index]['items'] = $this->mergeItemDefaults($result[$item_index]['items'], $item_defaults, $section_id, $indexed_items, $result[$item_index]['id']);
    }
   }
   
   $indexed_items[$section_id . '.' . $result[$item_index]['id']] = $result[$item_index];
  }
  
  return $result;
 }
}

/* End of file formsconfigmerger.php */
/* Location: ./WalkerCMS/helpers/forms/formsconfigmerger.php */