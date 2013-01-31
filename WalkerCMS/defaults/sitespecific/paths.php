<?php
// --------------------------------------------------------------
// Change to the current working directory.
// --------------------------------------------------------------
chdir(__DIR__);

// --------------------------------------------------------------
// The path to the storage directory.
// --------------------------------------------------------------
$paths['storage'] = '../storage';

// --------------------------------------------------------------
// The path to the public directory.
// --------------------------------------------------------------
$paths['public'] = '../public';

// --------------------------------------------------------------
// The path to the public directory.
// --------------------------------------------------------------
$paths['site_specific'] = './';

$paths['base'] = '../public';

// --------------------------------------------------------------
// Define the directory separator for the environment.
// --------------------------------------------------------------
if ( ! defined('DS'))
{
 define('DS', DIRECTORY_SEPARATOR);
}

// --------------------------------------------------------------
// Define each constant if it hasn't been defined.
// --------------------------------------------------------------
foreach ($paths as $name => $path)
{
 if ( ! isset($GLOBALS['laravel_paths'][$name]))
 {
  $GLOBALS['laravel_paths'][$name] = realpath($path).DS;
 }
}