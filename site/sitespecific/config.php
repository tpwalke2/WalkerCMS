<?php
  $walkercms_config['organization_name'] = 'WalkerCMS';
  $walkercms_config['organization_full_title'] = 'WalkerCMS';
  $walkercms_config['organization_slogan'] = '';
  $walkercms_config['description'] = 'WalkerCMS is a file-based content-management system (CMS) that is suited primarily for small websites.';
  $walkercms_config['keywords'] = 'content management system walkercms cms php laravel';
  $walkercms_config['contact_page']  = 'contact';
  $walkercms_config['contact_email'] = 'tpwalke2@gmail.com';
  $walkercms_config['contact_name']  = 'Tom Walker';

  $pageDefaults['perform_caching'] = false;
  $pageDefaults['show_in_nav'] = true;

  $pages['home']          = array('menu_title' => 'Home');
  $pages['documentation'] = array('menu_title' => 'Documentation', 'page_title' => 'Documentation');
  $pages['about']         = array('menu_title' => 'About', 'page_title' => 'About', 'sub_nav_on_page' => true);
  $pages['history']       = array('menu_title' => 'History', 'page_title' => 'About', 'show_in_nav' => false, 'parent' => 'about');
  $pages['faq']           = array('menu_title' => 'FAQ', 'page_title' => 'FAQ', 'show_in_nav' => false, 'parent' => 'about');
  $pages['source']        = array('menu_title' => 'Source', 'page_title' => 'Source Code');
  $pages['contact']       = array('menu_title' => 'Contact', 'page_title' => 'Contact');
?>