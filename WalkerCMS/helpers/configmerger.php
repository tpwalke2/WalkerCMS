<?php
class ConfigMerger implements IConfigMerger
{
 public function merge($pages, $page_defaults, $config)
 {
  foreach ($pages as $pageID=>$page)
  {
   $config['pages'][$pageID] = array();
   
   foreach ($page_defaults as $key=>$val)
   {
    $config['pages'][$pageID][$key] = $val;
   }
   
   foreach ($page as $key=>$val)
   {
    $config['pages'][$pageID][$key] = $val;
   }
   
   $config['pages'][$pageID]['id'] = $pageID;
  }
  
  return $config;
 }
}

/* End of file configmerger.php */
/* Location: ./WalkerCMS/helpers/configmerger.php */