<?php // Open Translation Engine - History Page v0.1.5
// dev TODO - pagination

namespace Attogram;

$ote = new ote( $this );

$title = '🔭 History';
$this->pageHeader($title);
print '<div class="container"><h1 class="squished">' . $title . '</h1>';

$sql = 'SELECT * FROM history ORDER BY date DESC, count DESC LIMIT 500';
$hr = $this->database->query($sql);

print '<div class="row" style="padding:1px; font-weight:bold;">
  <div class="col-xs-4 col-sm-5">Word</div>
  <div class="col-xs-4 col-sm-1 small">#</div>
  <div class="col-xs-4 col-sm-2 small">Day</div>
  <div class="clearfix visible-xs-block"></div>
  <div class="col-xs-4 col-sm-2 small">Source</div>
  <div class="col-xs-4 col-sm-2 small">Target</div>
</div>';

foreach( $hr as $h ) {

  $sl_code = $ote->get_language_code_from_id($h['sl']);
  $sl_name = $ote->get_language_name_from_id($h['sl']);
  $tl_code = $ote->get_language_code_from_id($h['tl']);
  $tl_name = $ote->get_language_name_from_id($h['tl']);

  $url = $this->path . '/word/' . $sl_code . '/' . $tl_code . '/' . urlencode($h['word']);

  print '<div class="row" style="border:1px solid #eeeeee; padding:0px;">
    <div class="col-xs-4 col-sm-5"><a href="' . $url . '"><h2 style="display:inline;">' . $this->webDisplay($h['word']) . '</h2></a></div>
    <div class="col-xs-4 col-sm-1 small">' . $h['count'] . '</div>
    <div class="col-xs-4 col-sm-2 small">' . $h['date'] . '</div>
    <div class="clearfix visible-xs-block"></div>
    <div class="col-xs-4 col-sm-2 small">' . ($sl_name ? $sl_name : '*') . '</div>
    <div class="col-xs-4 col-sm-2 small">' . ($tl_name ? $tl_name : '*') . '</div>
  </div>';
}

print '</div>';
$this->pageFooter();
