@if ($show_ie_warning)
<!--[if lte IE {{ $minimum_ie_version }}]>
<style type="text/css">
#getnewie{border:3px solid #c33; margin:8px 0; background:#fcc; color:#000;}
#getnewie h4{margin:8px; padding:0;}
#getnewie p{margin:8px; padding:0;}
#getnewie p a.getnewie{font-weight:bold; color:#006;}
#getnewie p a.newieexpl{font-weight:bold; color:#006;}
</style>
<div id="getnewie">
<h4>Did you know that your web browser is out of date?</h4>
<p>To get the best possible experience using this website we recommend that you <a href="http://browsehappy.com/">upgrade to a different browser</a>. If you're using a computer at work you should contact your IT-administrator. Either way, we would like to encourage you to stop using IE{{ $minimum_ie_version }} and try a more secure and Web Standards-friendly browser.</p>
</div>
<![endif]-->
@endif
