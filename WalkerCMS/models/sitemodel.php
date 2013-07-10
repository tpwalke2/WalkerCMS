<?php
class SiteModel
{
 public function has_custom_html_header()
 {
  return File::exists(path('site_specific') . 'htmlheaders/site');
 } 
}

/* End of file sitemodel.php */
/* Location: ./WalkerCMS/models/sitemodel.php */