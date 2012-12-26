<a name="top"></a>
<div id="wrap">
 <div id="header">
  <div id="header-links">
   <p>
    <a href="home">Home</a>
   </p>
  </div>
 </div>
 <div id="header-photo">
   <h1 id="logo-text"><a href="home" title=""><?= $organization_name ?></a></h1>
   <h2 id="slogan"><?= $organization_slogan ?></h2>
 </div>
 <?php if ($has_page_specific_header): ?>
  <?= $page_specific_header ?>
 <?php endif; ?>
 <?= $nav ?>
 <div id="content-wrap" class="content-<?= $content_page_id ?><?= ($has_sub_nav && $has_secondary_content ? ' three-col' : ($has_secondary_content || $has_sub_nav ? ' two-col' : ' one-col')) ?>">
  <?php if ($has_sub_nav): ?>
  <div id="sidebar">
    <?= $sub_nav ?>
  </div>
  <?php endif ?>

  <?php if ($has_secondary_content): ?>
  <div id="<?= ($has_sub_nav ? 'rightcolumn' : 'sidebar') ?>">
    <?= $secondary_content ?>
  </div>
  <?php endif ?>

  <div id="main">
    <?= $page_content ?>
  </div>
 </div>
 <div id="footer-wrap">
   <div id="footer">
     <p>
      &copy; 2012 Walker Software Consulting
            &nbsp;&nbsp;&nbsp;&nbsp;
      <a href="http://www.bluewebtemplates.com/" title="Website Templates" target="_blank">website templates</a> by <a href="http://www.styleshout.com/" title="styleshout templates" target="_blank">styleshout</a>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="home">Home</a> |
            <a href="http://validator.w3.org/check?uri=referer" title="Valid XHML" target="_blank">XHTML</a> |
      <a href="http://jigsaw.w3.org/css-validator/check/referer" title="Valid CSS" target="_blank">CSS</a>
     </p>
     <?php if ($has_page_specific_footer): ?>
      <?= $page_specific_footer ?>
     <?php endif; ?>
   </div>
 </div>
</div>