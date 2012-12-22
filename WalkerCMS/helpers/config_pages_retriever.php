<?php
class ConfigPagesRetriever
{
 public function get_pages()
 {
  $result = array();

  foreach (Config::get('walkercms.pages') as $id => $page_definition)
  {
   $result[$id] = IoC::resolve('page_model', array($page_definition));
  }

  return $result;
 }
}

/* End of file config_pages_retriever.php */
/* Location: ./WalkerCMS/helpers/config_pages_retriever.php */