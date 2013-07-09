<?php
if (! function_exists('form'))
{
 function form($form_id)
 {
  return WalkerCMS::generate_form($form_id);
 }
}

if (!isset($inclusion_file)) { $inclusion_file = $page_id; }
$include_path = path('site_specific') . "$inclusion_type/$inclusion_file";
if (File::exists($include_path))
{
 $view = new View("path: $include_path", $this->data());
 echo $view->render();
}

/* End of file page_inclusion.php */
/* Location: ./WalkerCMS/views/partials/page_inclusion.php */