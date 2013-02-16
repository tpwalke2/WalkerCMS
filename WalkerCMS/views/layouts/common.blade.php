{{-- @if file_exists(path('site_specific') . 'template/layout_template.php') --}}
<!DOCTYPE html>
<html lang="en">
 <!-- Copyright &copy; {{ date('Y') }} by {{ $organization_full_title }} All Rights Reserved. -->
 <!-- Generated on {{ date('l\, \t\h\e jS \o\f F Y \a\t g:i:s A T') }} -->
 <head>
  @include('partials.html_header')
 </head>
 <body>
  @include('partials.ie_warning')
  @include('partials.inner_template')
 </body>
</html>