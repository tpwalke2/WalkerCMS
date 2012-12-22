<?php
class NavItemConverter
{
 private $_custom_nav_content_retriever = null;

 function __construct($content_retriever)
 {
  $this->_custom_nav_content_retriever = $content_retriever;
 }

 public function convert($pages, $page, $current_page)
 {
  $result = array(
    'page_id' => $page->get_id(),
    'tooltip' => htmlentities($page->get_page_title() == '' ? $page->get_menu_title() : $page->get_page_title()),
    'description' => htmlentities($page->get_menu_title() == '' ? $page->get_page_title() : $page->get_menu_title()),
  );

  $result['is_external']        = ($page->get_external_url() != '');
  $result['is_override']        = ($page->get_override_url() != '');
  $result['url']                = ($result['is_external'] ? $page->get_external_url() : ($result['is_override'] ? $page->get_override_url() : $page->get_id()));
  $result['is_active_section']  = false;
  $result['generate_link']      = true;
  $result['has_custom_content'] = false;
  $result['custom_content']     = '';

  if ($page->has_custom_nav())
  {
   $result['has_custom_content'] = true;
   $result['custom_content'] = $this->_custom_nav_content_retriever->retrieve($pages, $page);
   $result['generate_link'] = false;
  } elseif ($page->get_id() == $current_page->get_id()) {
   $result['is_active_section'] = true;
   $result['generate_link'] = false;
  } elseif ($page->get_id() == $current_page->get_parent()) {
   $result['is_active_section'] = true;
  }

  return $result;
 }
}

/* End of file nav_item_converter.php */
/* Location: ./WalkerCMS/helpers/nav_item_converter.php */