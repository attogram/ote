<?php // Open Translation Engine - Homepage
require_once('ote.class.php');

$ote->template_feed_url = '<link rel="alternate" type="application/rss+xml" title="RSS" href="' . HOSTNAME . 'feed/">';
$ote->show_header(LANG_1_NAME . ' = ' . LANG_2_NAME);
print $ote->get_template('home.html');
$ote->show_footer(); 
