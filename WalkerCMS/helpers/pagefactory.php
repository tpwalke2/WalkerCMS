<?php
class PageFactory implements IPageFactory
{
 private $_logger = null;
 
 function __construct($logger)
 {
  $this->_logger = $logger;
 }
 
 public function create($page_definition)
 {
  $this->_logger->debug('[WalkerCMS] Creating page model instance');
  return new PageModel($page_definition);
 }
}

/* End of file pagefactory.php */
/* Location: ./WalkerCMS/helpers/pagefactory.php */