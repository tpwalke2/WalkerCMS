<?php
class PagesConfigMerger implements IConfigMerger
{
 public function merge($pages, $pages_defaults, $config)
 {
  foreach ($pages as $pageID=>$page)
  {
   $config['pages'][$pageID] = array();
   
   foreach ($pages_defaults as $key=>$val)
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

/* End of file pagesconfigmerger.php */
/* Location: ./WalkerCMS/helpers/pagesconfigmerger.php */