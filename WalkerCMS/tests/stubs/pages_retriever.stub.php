<?php
class PagesRetriever_Stub
{
 private $_pages = null;

 function __construct($pages)
 {
  $this->_pages = $pages;
 }

 public function get_pages()
 {
  return $this->_pages;
 }
}

/* End of file pages_retriever.stub.php */
/* Location: ./WalkerCMS/tests/stubs/pages_retriever.stub.php */