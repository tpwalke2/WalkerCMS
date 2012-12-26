<?php
use Laravel\View;
require_once(path('app') . 'helpers/interfaces/view_adapter.php');

class ViewAdapter implements IViewAdapter
{
 public function generate_view($name, $data)
 {
  return View::make($name, $data);
 }
}

/* End of file view_adapter.php */
/* Location: ./WalkerCMS/helpers/laravel/view_adapter.php */