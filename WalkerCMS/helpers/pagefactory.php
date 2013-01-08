<?php
class PageFactory
{
 public function create($page_definition)
 {
  return new PageModel($page_definition);
 }
}

/* End of file pagefactory.php */
/* Location: ./WalkerCMS/helpers/pagefactory.php */