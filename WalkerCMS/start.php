<?php

use Laravel\IoC;

/*
 |--------------------------------------------------------------------------
| PHP Display Errors Configuration
|--------------------------------------------------------------------------
|
| Since Laravel intercepts and displays all errors with a detailed stack
| trace, we can turn off the display_errors ini directive. However, you
| may want to enable this option if you ever run into a dreaded white
| screen of death, as it can provide some clues.
|
*/

ini_set('display_errors', 'On');

/*
 |--------------------------------------------------------------------------
| Laravel Configuration Loader
|--------------------------------------------------------------------------
|
| The Laravel configuration loader is responsible for returning an array
| of configuration options for a given bundle and file. By default, we
| use the files provided with Laravel; however, you are free to use
| your own storage mechanism for configuration arrays.
|
*/

Laravel\Event::listen(Laravel\Config::loader, function($bundle, $file)
{
 return Laravel\Config::file($bundle, $file);
});

/*
 |--------------------------------------------------------------------------
| Register Class Aliases
|--------------------------------------------------------------------------
|
| Aliases allow you to use classes without always specifying their fully
| namespaced path. This is convenient for working with any library that
| makes a heavy use of namespace for class organization. Here we will
| simply register the configured class aliases.
|
*/

$aliases = Laravel\Config::get('application.aliases');

Laravel\Autoloader::$aliases = $aliases;

/*
 |--------------------------------------------------------------------------
| Auto-Loader Mappings
|--------------------------------------------------------------------------
|
| Registering a mapping couldn't be easier. Just pass an array of class
| to path maps into the "map" function of Autoloader. Then, when you
| want to use that class, just use it. It's simple!
|
*/

Autoloader::map(array(
'Base_Controller' => path('app').'controllers/base.php',
));

/*
 |--------------------------------------------------------------------------
| Auto-Loader Directories
|--------------------------------------------------------------------------
|
| The Laravel auto-loader can search directories for files using the PSR-0
| naming convention. This convention basically organizes classes by using
| the class namespace to indicate the directory structure.
|
*/

Autoloader::directories(array(
path('app').'models',
path('app').'helpers',
));

/*
 |--------------------------------------------------------------------------
| Laravel View Loader
|--------------------------------------------------------------------------
|
| The Laravel view loader is responsible for returning the full file path
| for the given bundle and view. Of course, a default implementation is
| provided to load views according to typical Laravel conventions but
| you may change this to customize how your views are organized.
|
*/

Event::listen(View::loader, function($bundle, $view)
{
 return View::file($bundle, $view, Bundle::path($bundle).'views');
});

/*
 |--------------------------------------------------------------------------
| Laravel Language Loader
|--------------------------------------------------------------------------
|
| The Laravel language loader is responsible for returning the array of
| language lines for a given bundle, language, and "file". A default
| implementation has been provided which uses the default language
| directories included with Laravel.
|
*/

Event::listen(Lang::loader, function($bundle, $language, $file)
{
 return Lang::file($bundle, $language, $file);
});

/*
 |--------------------------------------------------------------------------
| Attach The Laravel Profiler
|--------------------------------------------------------------------------
|
| If the profiler is enabled, we will attach it to the Laravel events
| for both queries and logs. This allows the profiler to intercept
| any of the queries or logs performed by the application.
|
*/

if (Config::get('application.profiler'))
{
 Profiler::attach();
}

/*
 |--------------------------------------------------------------------------
| Enable The Blade View Engine
|--------------------------------------------------------------------------
|
| The Blade view engine provides a clean, beautiful templating language
| for your application, including syntax for echoing data and all of
| the typical PHP control structures. We'll simply enable it here.
|
*/

Blade::sharpen();

/*
 |--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| We need to set the default timezone for the application. This controls
| the timezone that will be used by any of the date methods and classes
| utilized by Laravel or your application. The timezone may be set in
| your application configuration file.
|
*/

date_default_timezone_set(Config::get('application.timezone'));

/*
 |--------------------------------------------------------------------------
| Start / Load The User Session
|--------------------------------------------------------------------------
|
| Sessions allow the web, which is stateless, to simulate state. In other
| words, sessions allow you to store information about the current user
| and state of your application. Here we'll just fire up the session
| if a session driver has been configured.
|
*/

if ( ! Request::cli() and Config::get('session.driver') !== '')
{
 Session::load();
}

/*
 * Setting up dependency injection for WalkerCMS
*/

require_once(path('app') . 'helpers/page_factory.php');
IoC::singleton('page_factory', function()
{
 return new PageFactory();
});

require_once(path('app') . 'helpers/laravel/config_adapter.php');
IoC::singleton('config_adapter', function()
{
 return new ConfigAdapter();
});

require_once(path('app') . 'helpers/laravel/logger_adapter.php');
IoC::singleton('logger', function ()
{
 return new LoggerAdapter();
});

require_once(path('app') . 'helpers/laravel/view_adapter.php');
IoC::singleton('view_adapter', function()
{
 return new ViewAdapter();
});

require_once(path('app') . 'helpers/config_pages_retriever.php');
IoC::singleton('pages_retriever', function()
{
 return new ConfigPagesRetriever(IoC::resolve('page_factory'), IoC::resolve('config_adapter'));
});

require_once(path('app') . 'helpers/page_id_validator.php');
IoC::singleton('page_id_validator', function()
{
 return new PageIDValidator();
});

require_once(path('app') . 'helpers/parent_retriever.php');
IoC::singleton('parent_retriever', function()
{
 return new ParentRetriever();
});

require_once(path('app') . 'helpers/sub_nav_required_determiner.php');
IoC::singleton('sub_nav_required_determiner', function()
{
 return new SubNavRequiredDeterminer(IoC::resolve('parent_retriever'));
});

require_once(path('app') . 'helpers/template_data_generator.php');
IoC::singleton('template_data_generator', function()
{
 return new TemplateDataGenerator(IoC::resolve('sub_nav_required_determiner'),
                                  IoC::resolve('config_adapter'),
                                  IoC::resolve('logger'));
});

require_once(path('app') . 'helpers/custom_nav_content_data_generator.php');
IoC::singleton('custom_nav_content_data_generator', function()
{
 return new CustomNavContentDataGenerator('nav');
});
IoC::singleton('custom_sub_nav_content_data_generator', function()
{
 return new CustomNavContentDataGenerator('subnav');
});

require_once(path('app') . 'helpers/custom_content_retriever.php');
IoC::singleton('custom_nav_content_retriever', function()
{
 return new CustomContentRetriever(IoC::resolve('custom_nav_content_data_generator'),
                                   IoC::resolve('view_adapter'));
});
IoC::singleton('custom_sub_nav_content_retriever', function()
{
 return new CustomContentRetriever(IoC::resolve('custom_sub_nav_content_data_generator'),
                                   IoC::resolve('view_adapter'));
});

require_once(path('app') . 'helpers/nav_item_converter.php');
IoC::singleton('nav_item_converter', function()
{
 return new NavItemConverter(IoC::resolve('custom_nav_content_retriever'));
});
IoC::singleton('sub_nav_item_converter', function()
{
 return new NavItemConverter(IoC::resolve('custom_sub_nav_content_retriever'));
});

require_once(path('app') . 'helpers/top_level_page_matcher.php');
IoC::singleton('top_level_page_matcher', function()
{
 return new TopLevelPageMatcher();
});
require_once(path('app') . 'helpers/page_child_matcher.php');
IoC::singleton('page_child_matcher', function()
{
 return new PageChildMatcher();
});

require_once(path('app') . 'helpers/topmost_subnav_parent_retriever.php');
IoC::singleton('topmost_subnav_parent_retriever', function()
{
 return new TopMostSubNavParentRetriever();
});

require_once(path('app') . 'helpers/nav_data_generator.php');
IoC::singleton('nav_data_generator', function()
{
 return new NavDataGenerator(IoC::resolve('nav_item_converter'), IoC::resolve('top_level_page_matcher'), IoC::resolve('topmost_subnav_parent_retriever'), IoC::resolve('config_adapter'), true);
});
IoC::singleton('sub_nav_data_generator', function()
{
 return new NavDataGenerator(IoC::resolve('sub_nav_item_converter'), IoC::resolve('page_child_matcher'), IoC::resolve('topmost_subnav_parent_retriever'), IoC::resolve('config_adapter'), false);
});

require_once(path('app') . 'helpers/first_child_page_retriever.php');
IoC::singleton('first_child_page_retriever', function()
{
 return new FirstChildPageRetriever(IoC::resolve('page_child_matcher'), IoC::resolve('topmost_subnav_parent_retriever'));
});

require_once(path('app') . 'helpers/content_source_page_retriever.php');
IoC::singleton('content_source_page_retriever', function()
{
 return new ContentSourcePageRetriever(IoC::resolve('first_child_page_retriever'), IoC::resolve('logger'));
});

IoC::register('controller: page', function()
{
 return new Page_Controller(IoC::resolve('pages_retriever'),
   IoC::resolve('page_id_validator'),
   IoC::resolve('template_data_generator'),
   IoC::resolve('nav_data_generator'),
   IoC::resolve('sub_nav_data_generator'),
   IoC::resolve('content_source_page_retriever'),
   IoC::resolve('logger'));
});