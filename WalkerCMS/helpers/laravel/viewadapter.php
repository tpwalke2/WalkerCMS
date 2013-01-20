<?php
use Laravel\View;

class ViewAdapter implements IViewAdapter
{
 public function generate_view($name, $data)
 {
  return View::make($name, $data);
 }
}

/* End of file viewadapter.php */
/* Location: ./WalkerCMS/helpers/laravel/viewadapter.php */