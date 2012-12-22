<?php
use Laravel\Log;

class Page_Controller extends Base_Controller
{
 private $_pages_retriever = null;
 private $_page_id_validator = null;
 private $_template_data_generator = null;
 private $_nav_data_generator = null;
 
 function __construct($pages_retriever,
                      $page_id_validator,
                      $template_data_generator,
                      $nav_data_generator)
 {
  parent::__construct();
  $this->_pages_retriever = $pages_retriever;
  $this->_page_id_validator = $page_id_validator;
  $this->_template_data_generator = $template_data_generator;
  $this->_nav_data_generator = $nav_data_generator;
 }
	public function action_page($page_id = 'home')
	{
	 Log::debug("[WalkerCMS] Beginning generation of page: $page_id");
	 $pages = $this->_pages_retriever->get_pages();
	 $page_id = $this->_page_id_validator->get_validated_page_id($pages, $page_id);
	 Log::debug("[WalkerCMS] Validated page ID: $page_id");
	 $current_page = $pages[$page_id];
	 
   $page_title = $current_page->get_page_title();
   $organization_name = Config::get('walkercms.organization_name');
   $organization_full_title = Config::get('walkercms.organization_full_title');
   $common_data = $this->_template_data_generator->generate_data($pages, $current_page);
   $nav_data = $this->_nav_data_generator->generate_data($pages, $current_page);
   $sub_nav_data = array(
     'nav_id' => 'subNav',
     'is_primary_nav' => false,
     'nav_items' => array(),
     'organization_name' => $organization_name,
     );
   $content_data = array(
     'page_id' => $page_id,
     'inclusion_type' => 'content',
   );
   $secondary_content_data = array(
     'page_id' => $page_id,
     'inclusion_type' => 'secondarycontent',
   );
   $page_specific_html_header_data = array(
     'page_id' => $page_id,
     'inclusion_type' => 'htmlheaders',
   );
   $page_specific_header_data = array(
     'page_id' => $page_id,
     'inclusion_type' => 'headers',
   );
   $page_specific_footer_data = array(
     'page_id' => $page_id,
     'inclusion_type' => 'footers',
   );
   
   return View::make('layouts.common', $common_data)
                 ->nest('page_specific_html_header', 'partials.page_inclusion', $page_specific_html_header_data)
                 ->nest('nav', 'partials.nav', $nav_data)
                 ->nest('sub_nav', 'partials.nav', $sub_nav_data)
                 ->nest('page_content', 'partials.page_inclusion', $content_data)
                 ->nest('secondary_content', 'partials.page_inclusion', $secondary_content_data)
                 ->nest('page_specific_header', 'partials.page_inclusion', $page_specific_header_data)
                 ->nest('page_specific_footer', 'partials.page_inclusion', $page_specific_footer_data);
	}
}

/* End of file page.php */
/* Location: ./WalkerCMS/controllers/page.php */