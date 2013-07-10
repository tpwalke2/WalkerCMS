<title>{{ $page_title }}</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<meta name="robots" content="index, follow" />
<meta name="generator" content="WalkerCMS" />
<meta name="author" content="Walker Software Consulting" />
<meta name="rating" content="general" />
<meta name="description" content="{{ $site_description }}" />
<meta name="keywords" content="{{ $site_keywords }}" />
<link href="/styles/default.css" media="all" type="text/css" rel="stylesheet" />
<link href="/styles/site.css" media="all" type="text/css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
<link href="/scripts/fancybox/jquery.fancybox.css" media="all" type="text/css" rel="stylesheet" />
<script src="/scripts/fancybox/jquery.fancybox.pack.js" type="text/javascript"></script>
<script src="/scripts/jquery.validate.min.js" type="text/javascript"></script>
<script src="/scripts/jquery.form.min.js" type="text/javascript"></script>
<script src="/scripts/default.js" type="text/javascript"></script>
@if ($has_site_specific_html_header)
 {{ $site_specific_html_header }}
@endif
@if ($has_page_specific_html_header)
 {{ $page_specific_html_header }}
@endif
@if ($has_page_specific_stylesheet)
 <link href="/styles/{{$page_id}}.css" media="all" type="text/css" rel="stylesheet" />
@endif
@if ($has_page_specific_javascript)
 <script src="/scripts/{{$page_id}}.js" type="text/javascript"></script>
@endif