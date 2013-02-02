<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <!-- Generated on {{ date('l\, \t\h\e jS \o\f F Y \a\t g:i:s A T') }} -->
 <head>
  <title>{{ $page_title }}</title>
  <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="robot" content="index, follow" />
  <meta http-equiv="robots" content="index, follow" />
  <meta http-equiv="expires" content="7" />
  <meta name="Generator" content="WalkerCMS" />
  <meta name="Author" content="Walker Software Consulting" />
  <meta name="MSSmartTagsPreventParsing" content="true" />
  <meta name="resource-type" content="document" />
  <meta name="Content-Script-Type" content="text/javascript" />
  <meta name="rating" content="general" />
  <meta name="revisit" content="14 days" />
  <meta name="search" content="100%" />
  <meta name="index" content="100%" />
  <meta name="lang" content="en" />
  <meta name="Designer" content="Tom Walker" />
  <meta name="Company" content="{{ $organization_full_title }}" />
  <meta name="title" content="{{ $organization_name }}" />
  <meta name="copyright" content="Copyright &copy; {{ date('Y') }} by {{ $organization_full_title }} All Rights Reserved." />
  <meta name="description" content="{{ $site_description }}" />
  <meta name="keywords" content="{{ $site_keywords }}" />
  {{ HTML::style('/styles/default.css') }}
  {{ HTML::style('/styles/site.css') }}
  {{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js') }}
  {{ HTML::style("/scripts/fancybox/jquery.fancybox.css") }}
  {{ HTML::script('/scripts/fancybox/jquery.fancybox.pack.js') }}
  {{ HTML::script('/scripts/jquery.validate.min.js') }}
  {{ HTML::script('/scripts/jquery.form.min.js') }}
  {{ HTML::script('/scripts/default.js') }}
  @if ($has_page_specific_html_header)
   {{ $page_specific_html_header }}
  @endif
  @if ($has_page_specific_stylesheet)
   {{ HTML::style("/styles/${page_id}.css") }}
  @endif
  @if ($has_page_specific_javascript)
   {{ HTML::script("/scripts/${page_id}.js") }}
  @endif
 </head>
 <body>
  @include('partials.ie_warning')
  @include('partials.inner_template')
 
 </body>
</html>
