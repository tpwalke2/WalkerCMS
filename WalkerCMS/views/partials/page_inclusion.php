<?php
$include_path = path('site_specific') . "$inclusion_type/$page_id";
if (File::exists($include_path))
{
 require_once(path('site_specific') . "$inclusion_type/$page_id");
}

/* End of file page_inclusion.php */
/* Location: ./WalkerCMS/views/partials/page_inclusion.php */