<div id="outer">
  <div id="inner" class="<?= ($contains_secondary_content ? 'secondaryContent' : '') ?>">
    <div id="upbg"></div>
    <div id="header">
      <a href="home" class="homeLink" title="<?= $organization_name ?> Home"><img src="/images/slogan_small.png" alt="<?= $organization_full_title ?> logo" /></a>
      <?php if ($has_page_specific_header): ?>
        <?= $page_specific_header ?>
      <?php endif; ?>
    </div>
    <div id="splash"></div>
    <?= $nav ?>
    <div id="navbottom"></div>
    <div id="content" class="content-<?= $content_page_id ?><?= ($contains_sub_nav ? ' subNav' : '')  ?>">
      <?php if ($contains_sub_nav): ?>
        <?= $sub_nav ?>
      <?php endif; ?>
      <div id="innerContent">
        <?= $page_content ?>
      </div>
    </div>
    <?php if ($contains_secondary_content): ?>
    <div id="secondarycontent">
      <?= $secondary_content ?>
    </div>
    <?php endif; ?>
    <div id="footer">
      <?php if ($has_page_specific_footer): ?>
        <?= $page_specific_footer ?>
      <?php endif; ?>
      <p>
        <span class="copyright">Copyright &copy; <?= date('Y') ?></span> by <a href="home" class="homeLink" title="<?= $organization_name ?>"><?= $organization_full_title ?></a> All Rights Reserved.<br />
        Website by Tom Walker<br />
        <a href="http://validator.w3.org/check?uri=referer" title="XHTML Validator" target="_blank">Valid XHTML</a>
      </p>
      <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-23056321-1']);
        _gaq.push(['_trackPageview']);

        (function() {
          var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
      </script>
    </div>
  </div>
</div>