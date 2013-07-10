<?php
interface IPageStore
{
 function get_all_pages();
 function get_page($id);
 function exists($id);
 function get_parent($of_page_id);
 function get_children($of_page_id);
 function get_all_by_properties($properties);
}

/* End of file ipagestore.php */
/* Location: ./WalkerCMS/data/interfaces/ipagestore.php */