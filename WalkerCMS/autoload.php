<?php
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
'Base_Controller'    => path('app') . 'controllers/base.php',
'Contact_Controller' => path('app') . 'controllers/contact.php',
'Main_Controller'    => path('app') . 'controllers/main.php',
'AppContext' => path('app') . 'models/appcontext.php',
'PageModel'  => path('app') . 'models/pagemodel.php',
'CacheAdapter'       => path('app') . 'helpers/laravel/cacheadapter.php',
'ConfigAdapter'      => path('app') . 'helpers/laravel/configadapter.php',
'InputAdapter'       => path('app') . 'helpers/laravel/inputadapter.php',
'LoggerAdapter'      => path('app') . 'helpers/laravel/loggeradapter.php',
'RedirectAdapter'    => path('app') . 'helpers/laravel/redirectadapter.php',
'RequestAdapter'     => path('app') . 'helpers/laravel/requestadapter.php',
'ResponseAdapter'    => path('app') . 'helpers/laravel/responseadapter.php',
'SessionAdapter'     => path('app') . 'helpers/laravel/sessionadapter.php',
'SwiftMailerAdapter' => path('app') . 'helpers/laravel/swiftmaileradapter.php',
'ValidationWrapper'  => path('app') . 'helpers/laravel/validationwrapper.php',
'ValidatorAdapter'   => path('app') . 'helpers/laravel/validatoradapter.php',
'ViewAdapter'        => path('app') . 'helpers/laravel/viewadapter.php',
'ConfigPagesRetriever'                  => path('app') . 'helpers/configpagesretriever.php',
'ContactFormDataGenerator'              => path('app') . 'helpers/contactformdatagenerator.php',
'ContactFormInvalidSubmissionProcessor' => path('app') . 'helpers/contactforminvalidsubmissionprocessor.php',
'ContactFormResponseRetriever'          => path('app') . 'helpers/contactformresponseretriever.php',
'ContactFormResultDataFactory'          => path('app') . 'helpers/contactformresultdatafactory.php',
'ContactFormSpamSubmissionProcessor'    => path('app') . 'helpers/contactformspamsubmissionprocessor.php',
'ContactFormValidatorRetriever'         => path('app') . 'helpers/contactformvalidatorretriever.php',
'ContactFormValidSubmissionProcessor'   => path('app') . 'helpers/contactformvalidsubmissionprocessor.php',
'ContentDataGenerator'                  => path('app') . 'helpers/contentdatagenerator.php',
'ContentSourcePageRetriever'            => path('app') . 'helpers/contentsourcepageretriever.php',
'ContextFactory'                        => path('app') . 'helpers/contextfactory.php',
'CustomContentRetriever'                => path('app') . 'helpers/customcontentretriever.php',
'FirstChildPageRetriever'               => path('app') . 'helpers/firstchildpageretriever.php',
'ICacheAdapter'                         => path('app') . 'helpers/icacheadapter.php',
'IConfigAdapter'                        => path('app') . 'helpers/iconfigadapter.php',
'IContextFactory'                       => path('app') . 'helpers/icontextfactory.php',
'ICustomContentRetriever'               => path('app') . 'helpers/icustomcontentretriever.php',
'IDataFactory'                          => path('app') . 'helpers/idatafactory.php',
'IDataGenerator'                        => path('app') . 'helpers/idatagenerator.php',
'IDataProcessor'                        => path('app') . 'helpers/idataprocessor.php',
'IInputAdapter'                         => path('app') . 'helpers/iinputadapter.php',
'ILoggerAdapter'                        => path('app') . 'helpers/iloggeradapter.php',
'IMailerAdapter'                        => path('app') . 'helpers/imaileradapter.php',
'INavItemConverter'                     => path('app') . 'helpers/inavitemconverter.php',
'IPageFactory'                          => path('app') . 'helpers/ipagefactory.php',
'IPageIDValidator'                      => path('app') . 'helpers/ipageidvalidator.php',
'IPageGenerator'                        => path('app') . 'helpers/ipagegenerator.php',
'IPageMatcher'                          => path('app') . 'helpers/ipagematcher.php',
'IPageRetriever'                        => path('app') . 'helpers/ipageretriever.php',
'IPagesRetriever'                       => path('app') . 'helpers/ipagesretriever.php',
'IRedirectAdapter'                      => path('app') . 'helpers/iredirectadapter.php',
'IRequestAdapter'                       => path('app') . 'helpers/irequestadapter.php',
'IRequiredDeterminer'                   => path('app') . 'helpers/irequireddeterminer.php',
'IResponseAdapter'                      => path('app') . 'helpers/iresponseadapter.php',
'IResponseRetriever'                    => path('app') . 'helpers/iresponseretriever.php',
'ISessionAdapter'                       => path('app') . 'helpers/isessionadapter.php',
'ITemplateDataGenerator'                => path('app') . 'helpers/itemplatedatagenerator.php',
'IValidationWrapper'                    => path('app') . 'helpers/ivalidationwrapper.php',
'IValidatorAdapter'                     => path('app') . 'helpers/ivalidatoradapter.php',
'IValidatorRetriever'                   => path('app') . 'helpers/ivalidatorretriever.php',
'IViewAdapter'                          => path('app') . 'helpers/iviewadapter.php',
'IViewWrapper'                          => path('app') . 'helpers/iviewwrapper.php',
'NavDataGenerator'                      => path('app') . 'helpers/navdatagenerator.php',
'NavItemConverter'                      => path('app') . 'helpers/navitemconverter.php',
'PageChildMatcher'                      => path('app') . 'helpers/pagechildmatcher.php',
'PageFactory'                           => path('app') . 'helpers/pagefactory.php',
'PageGenerator'                         => path('app') . 'helpers/pagegenerator.php',
'PageIDValidator'                       => path('app') . 'helpers/pageidvalidator.php',
'PageSpecificInclusionDataGenerator'    => path('app') . 'helpers/pagespecificinclusiondatagenerator.php',
'ParentRetriever'                       => path('app') . 'helpers/parentretriever.php',
'SubNavRequiredDeterminer'              => path('app') . 'helpers/subnavrequireddeterminer.php',
'TemplateDataGenerator'                 => path('app') . 'helpers/templatedatagenerator.php',
'TopLevelPageMatcher'                   => path('app') . 'helpers/toplevelpagematcher.php',
'TopMostSubNavParentRetriever'          => path('app') . 'helpers/topmostsubnavparentretriever.php',
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
));

/* End of file autoload.php */
/* Location: ./WalkerCMS/autoload.php */