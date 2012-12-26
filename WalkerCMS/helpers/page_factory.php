<?php
require_once(path('app') . 'models/page_model.php');

class PageFactory
{
 public function create($page_definition)
 {
  return new PageModel($page_definition);
 }
}

/* End of file page_factory.php */
/* Location: ./WalkerCMS/helpers/page_factory.php */