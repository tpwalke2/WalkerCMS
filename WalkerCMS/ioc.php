<?php
use Laravel\IoC;

/*
 * Setting up dependency injection for WalkerCMS
*/

IoC::singleton('page_factory', function()
{
 return new PageFactory();
});

IoC::singleton('config_adapter', function()
{
 return new ConfigAdapter();
});

IoC::singleton('logger', function ()
{
 return new LoggerAdapter();
});

IoC::singleton('cache_adapter', function()
{
 return new CacheAdapter();
});

IoC::singleton('view_adapter', function()
{
 return new ViewAdapter();
});

IoC::singleton('input_adapter', function()
{
 return new InputAdapter();
});

IoC::singleton('redirect_adapter', function()
{
 return new RedirectAdapter();
});

IoC::singleton('request_adapter', function()
{
 return new RequestAdapter();
});

IoC::singleton('response_adapter', function()
{
 return new ResponseAdapter();
});

IoC::singleton('mailer_adapter', function()
{
 return new SwiftMailerAdapter();
});

IoC::singleton('session_adapter', function()
{
 return new SessionAdapter();
});

IoC::singleton('validator_adapter', function()
{
 return new ValidatorAdapter();
});

IoC::register('inclusion_data_generator', function($inclusion_type)
{
 return new PageSpecificInclusionDataGenerator($inclusion_type, IoC::resolve('logger'));
});

IoC::singleton('pages_retriever', function()
{
 return new ConfigPagesRetriever(IoC::resolve('page_factory'), IoC::resolve('config_adapter'), IoC::resolve('logger'));
});

IoC::singleton('page_id_validator', function()
{
 return new PageIDValidator();
});

IoC::singleton('parent_retriever', function()
{
 return new ParentRetriever();
});

IoC::singleton('sub_nav_required_determiner', function()
{
 return new SubNavRequiredDeterminer(IoC::resolve('parent_retriever'));
});

IoC::singleton('template_data_generator', function()
{
 return new TemplateDataGenerator(IoC::resolve('sub_nav_required_determiner'),
   IoC::resolve('config_adapter'),
   IoC::resolve('logger'));
});

IoC::singleton('custom_nav_content_retriever', function()
{
 return new CustomContentRetriever(IoC::resolve('inclusion_data_generator', array('nav')),
   IoC::resolve('view_adapter'),
   IoC::resolve('logger'));
});
IoC::singleton('custom_sub_nav_content_retriever', function()
{
 return new CustomContentRetriever(IoC::resolve('inclusion_data_generator', array('subnav')),
   IoC::resolve('view_adapter'),
   IoC::resolve('logger'));
});

IoC::singleton('nav_item_converter', function()
{
 return new NavItemConverter(IoC::resolve('custom_nav_content_retriever'));
});
IoC::singleton('sub_nav_item_converter', function()
{
 return new NavItemConverter(IoC::resolve('custom_sub_nav_content_retriever'));
});

IoC::singleton('top_level_page_matcher', function()
{
 return new TopLevelPageMatcher();
});

IoC::singleton('page_child_matcher', function()
{
 return new PageChildMatcher();
});

IoC::singleton('topmost_subnav_parent_retriever', function()
{
 return new TopMostSubNavParentRetriever();
});

IoC::singleton('nav_data_generator', function()
{
 return new NavDataGenerator(IoC::resolve('nav_item_converter'),
   IoC::resolve('top_level_page_matcher'),
   IoC::resolve('topmost_subnav_parent_retriever'),
   IoC::resolve('config_adapter'),
   true,
   IoC::resolve('logger'));
});
IoC::singleton('sub_nav_data_generator', function()
{
 return new NavDataGenerator(IoC::resolve('sub_nav_item_converter'),
   IoC::resolve('page_child_matcher'),
   IoC::resolve('topmost_subnav_parent_retriever'),
   IoC::resolve('config_adapter'),
   false,
   IoC::resolve('logger'));
});

IoC::singleton('first_child_page_retriever', function()
{
 return new FirstChildPageRetriever(IoC::resolve('page_child_matcher'),
   IoC::resolve('topmost_subnav_parent_retriever'),
   IoC::resolve('logger'));
});

IoC::singleton('content_source_page_retriever', function()
{
 return new ContentSourcePageRetriever(IoC::resolve('first_child_page_retriever'),
   IoC::resolve('logger'));
});

IoC::singleton('contact_form_generator', function()
{
 return new ContactFormDataGenerator(IoC::resolve('config_adapter'), IoC::resolve('view_adapter'), IoC::resolve('logger'));
});

IoC::singleton('content_data_generator', function()
{
 return new ContentDataGenerator(
   IoC::resolve('inclusion_data_generator', array('content')),
   IoC::resolve('contact_form_generator'),
   IoC::resolve('logger'));
});

IoC::singleton('page_generator', function()
{
 return new PageGenerator(
   IoC::resolve('template_data_generator'),
   IoC::resolve('inclusion_data_generator', array('htmlheaders')),
   IoC::resolve('inclusion_data_generator', array('headers')),
   IoC::resolve('content_data_generator'),
   IoC::resolve('nav_data_generator'),
   IoC::resolve('sub_nav_data_generator'),
   IoC::resolve('inclusion_data_generator', array('subnav')),
   IoC::resolve('inclusion_data_generator', array('secondarycontent')),
   IoC::resolve('inclusion_data_generator', array('footers')),
   IoC::resolve('content_source_page_retriever'),
   IoC::resolve('logger'));
});

IoC::register('context_factory', function()
{
 return new ContextFactory(
   IoC::resolve('pages_retriever'), 
   IoC::resolve('page_id_validator'),
   IoC::resolve('session_adapter'),
   IoC::resolve('logger'));
});

IoC::register('controller: main', function()
{
 return new Main_Controller(
   IoC::resolve('context_factory'),
   IoC::resolve('page_generator'),
   IoC::resolve('config_adapter'),
   IoC::resolve('cache_adapter'),
   IoC::resolve('logger'));
});

IoC::register('controller: contact', function()
{
 return new Contact_Controller(
   IoC::resolve('pages_retriever'),
   IoC::resolve('contact_form_generator'),
   IoC::resolve('config_adapter'),
   IoC::resolve('input_adapter'),
   IoC::resolve('validator_adapter'),
   IoC::resolve('redirect_adapter'),
   IoC::resolve('request_adapter'),
   IoC::resolve('response_adapter'),
   IoC::resolve('view_adapter'),
   IoC::resolve('mailer_adapter'),
   IoC::resolve('logger')
 );
});

/* End of file ioc.php */
/* Location: ./WalkerCMS/ioc.php */