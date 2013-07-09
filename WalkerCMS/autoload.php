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
'Forms_Controller'   => path('app') . 'controllers/forms.php',
'Main_Controller'    => path('app') . 'controllers/main.php',
'IPageStore'         => path('app') . 'data/interfaces/ipagestore.php',
'CachingPageStore'   => path('app') . 'data/cachingpagestore.php',
'ConfigPageStore'    => path('app') . 'data/configpagestore.php',
'AppContext' => path('app') . 'models/appcontext.php',
'PageModel'  => path('app') . 'models/pagemodel.php',
'SiteModel'  => path('app') . 'models/sitemodel.php',
'IRulesGenerator'                => path('app') . 'helpers/forms/interfaces/irulesgenerator.php',
'BaseRulesGenerator'             => path('app') . 'helpers/forms/baserulesgenerator.php',
'EmailInputRulesGenerator'       => path('app') . 'helpers/forms/emailinputrulesgenerator.php',
'FormDataGenerator'              => path('app') . 'helpers/forms/formdatagenerator.php',
'FormGenerator'                  => path('app') . 'helpers/forms/formgenerator.php',
'FormInvalidSubmissionProcessor' => path('app') . 'helpers/forms/forminvalidsubmissionprocessor.php',
'FormResponseRetriever'          => path('app') . 'helpers/forms/formresponseretriever.php',
'FormResultDataFactory'          => path('app') . 'helpers/forms/formresultdatafactory.php',
'FormsConfigMerger'              => path('app') . 'helpers/forms/formsconfigmerger.php',
'FormsConfigValidator'           => path('app') . 'helpers/forms/formsconfigvalidator.php',
'FormSpamSubmissionProcessor'    => path('app') . 'helpers/forms/formspamsubmissionprocessor.php',
'FormValidatorRetriever'         => path('app') . 'helpers/forms/formvalidatorretriever.php',
'FormValidSubmissionProcessor'   => path('app') . 'helpers/forms/formvalidsubmissionprocessor.php',
'MultipleChoiceRulesGenerator'   => path('app') . 'helpers/forms/multiplechoicerulesgenerator.php',
'TextInputRulesGenerator'        => path('app') . 'helpers/forms/textinputrulesgenerator.php',
'ICache'                  => path('app') . 'helpers/interfaces/icache.php',
'IConfigAdapter'          => path('app') . 'helpers/interfaces/iconfigadapter.php',
'IConfigMerger'           => path('app') . 'helpers/interfaces/iconfigmerger.php',
'IConfigValidator'        => path('app') . 'helpers/interfaces/iconfigvalidator.php',
'IContextFactory'         => path('app') . 'helpers/interfaces/icontextfactory.php',
'ICustomContentRetriever' => path('app') . 'helpers/interfaces/icustomcontentretriever.php',
'IDataFactory'            => path('app') . 'helpers/interfaces/idatafactory.php',
'IDataGenerator'          => path('app') . 'helpers/interfaces/idatagenerator.php',
'IDataProcessor'          => path('app') . 'helpers/interfaces/idataprocessor.php',
'IFormDataGenerator'      => path('app') . 'helpers/interfaces/iformdatagenerator.php',
'IInputAdapter'           => path('app') . 'helpers/interfaces/iinputadapter.php',
'ILoggerAdapter'          => path('app') . 'helpers/interfaces/iloggeradapter.php',
'IMailerAdapter'          => path('app') . 'helpers/interfaces/imaileradapter.php',
'INavItemConverter'       => path('app') . 'helpers/interfaces/inavitemconverter.php',
'IPageFactory'            => path('app') . 'helpers/interfaces/ipagefactory.php',
'IPageIDValidator'        => path('app') . 'helpers/interfaces/ipageidvalidator.php',
'IPageGenerator'          => path('app') . 'helpers/interfaces/ipagegenerator.php',
'IPageMatcher'            => path('app') . 'helpers/interfaces/ipagematcher.php',
'IPageRetriever'          => path('app') . 'helpers/interfaces/ipageretriever.php',
'IRedirectAdapter'        => path('app') . 'helpers/interfaces/iredirectadapter.php',
'IRequestAdapter'         => path('app') . 'helpers/interfaces/irequestadapter.php',
'IRequiredDeterminer'     => path('app') . 'helpers/interfaces/irequireddeterminer.php',
'IResponseAdapter'        => path('app') . 'helpers/interfaces/iresponseadapter.php',
'IResponseRetriever'      => path('app') . 'helpers/interfaces/iresponseretriever.php',
'ISessionAdapter'         => path('app') . 'helpers/interfaces/isessionadapter.php',
'ITemplateDataGenerator'  => path('app') . 'helpers/interfaces/itemplatedatagenerator.php',
'IValidationWrapper'      => path('app') . 'helpers/interfaces/ivalidationwrapper.php',
'IValidatorAdapter'       => path('app') . 'helpers/interfaces/ivalidatoradapter.php',
'IValidatorRetriever'     => path('app') . 'helpers/interfaces/ivalidatorretriever.php',
'IViewAdapter'            => path('app') . 'helpers/interfaces/iviewadapter.php',
'IViewWrapper'            => path('app') . 'helpers/interfaces/iviewwrapper.php',
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
'ChainedConfigValidator'                => path('app') . 'helpers/chainedconfigvalidator.php',
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
'NavDataGenerator'                      => path('app') . 'helpers/navdatagenerator.php',
'NavItemConverter'                      => path('app') . 'helpers/navitemconverter.php',
'PageChildMatcher'                      => path('app') . 'helpers/pagechildmatcher.php',
'PageFactory'                           => path('app') . 'helpers/pagefactory.php',
'PageGenerator'                         => path('app') . 'helpers/pagegenerator.php',
'PageIDValidator'                       => path('app') . 'helpers/pageidvalidator.php',
'PagesConfigMerger'                     => path('app') . 'helpers/pagesconfigmerger.php',
'PagesConfigValidator'                  => path('app') . 'helpers/pagesconfigvalidator.php',
'PageSpecificInclusionDataGenerator'    => path('app') . 'helpers/pagespecificinclusiondatagenerator.php',
'ParentRetriever'                       => path('app') . 'helpers/parentretriever.php',
'SiteHTMLHeaderDataGenerator'           => path('app') . 'helpers/sitehtmlheaderdatagenerator.php',
'SubNavRequiredDeterminer'              => path('app') . 'helpers/subnavrequireddeterminer.php',
'TemplateDataGenerator'                 => path('app') . 'helpers/templatedatagenerator.php',
'TopLevelPageMatcher'                   => path('app') . 'helpers/toplevelpagematcher.php',
'TopMostSubNavParentRetriever'          => path('app') . 'helpers/topmostsubnavparentretriever.php',
'WalkerCMS'                             => path('app') . 'helpers/walkercms.php',
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