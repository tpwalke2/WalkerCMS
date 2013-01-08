<?php
class NavItemConverter
{
 private $_custom_nav_content_retriever = null;

 function __construct($content_retriever)
 {
  $this->_custom_nav_content_retriever = $content_retriever;
 }

 public function convert($working_page, $context)
 {
  $result = array(
    'page_id' => $working_page->get_id(),
    'tooltip' => htmlentities($working_page->get_page_title() == '' ? $working_page->get_menu_title() : $working_page->get_page_title()),
    'description' => htmlentities($working_page->get_menu_title() == '' ? $working_page->get_page_title() : $working_page->get_menu_title()),
  );

  $result['is_external']        = ($working_page->get_external_url() != '');
  $result['is_override']        = ($working_page->get_override_url() != '');
  $result['url']                = ($result['is_external'] ? $working_page->get_external_url() : ($result['is_override'] ? $working_page->get_override_url() : $working_page->get_id()));
  $result['is_active_section']  = false;
  $result['generate_link']      = true;
  $result['has_custom_content'] = false;
  $result['custom_content']     = '';

  if ($working_page->has_custom_nav())
  {
   $result['has_custom_content'] = true;
   $result['custom_content'] = $this->_custom_nav_content_retriever->retrieve_content($working_page, $context);
   $result['generate_link'] = false;
  }
  elseif ($working_page->get_id() == $context->get_current_page()->get_id())
  {
   $result['is_active_section'] = true;
   $result['generate_link'] = false;
  }
  elseif ($working_page->get_id() == $context->get_current_page()->get_parent())
  {
   $result['is_active_section'] = true;
  }

  return $result;
 }
}

/* End of file navitemconverter.php */
/* Location: ./WalkerCMS/helpers/navitemconverter.php */