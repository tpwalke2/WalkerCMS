<?php
class CustomContentRetriever
{
 private $_data_generator = null;

 function __construct($data_generator)
 {
  $this->_data_generator = $data_generator;
 }

 public function retrieve_content($pages, $page)
 {
  return View::make('partials.page_inclusion', $this->_data_generator->generate_data($pages, $page));
 }
}

/* End of file custom_content_retriever.php */
/* Location: ./WalkerCMS/helpers/custom_content_retriever.php */