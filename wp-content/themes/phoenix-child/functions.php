<?php
/**
 * Phoenix Child Theme Functions
 * Add custom code below
 */


$domain = $_SERVER['HTTP_HOST'];
if ($domain == 'mnassalska.com') {
add_action('wp_footer', 'add_googleanalytics');
}

function add_googleanalytics() { ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73161400-1', 'auto');
  ga('send', 'pageview');

</script>
<? }