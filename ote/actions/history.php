<?php // Open Translation Engine - History Page v0.0.2

namespace Attogram;

$ote = new ote($this->db, $this->log);

$title = '🔭 History';
$this->page_header($title);
print '<div class="container"><h1>' . $title . '</h1>';

$sql = 'SELECT * FROM history ORDER BY date DESC, count DESC';
$hr = $this->db->query($sql);

print '<div class="row" style="padding:1px; font-weight:bold;">
  <div class="col-xs-2">Word</div>
  <div class="col-xs-1">#</div>
  <div class="col-xs-3">Day</div>
  <div class="col-xs-3"><small>Source Language</small></div>
  <div class="col-xs-3"><small>Target Language</small></div>
</div>';

foreach( $hr as $h ) {
  $url = $this->path . '/word///' . urlencode($h['word']);
  $sl_code = $ote->get_language_code_from_id($h['sl']);
  if( !$sl_code ) {
    $sl_code = '*';
  }
  $tl_code = $ote->get_language_code_from_id($h['tl']);
  if( !$tl_code ) {
    $tl_code = '*';
  }
  print '<div class="row" style="border:1px solid #eeeeee; padding:0px;">
    <div class="col-xs-2"><a href="' . $url . '">' . htmlentities($h['word']) . '</a></div>
    <div class="col-xs-1">' . $h['count'] . '</div>
    <div class="col-xs-3">' . $h['date'] . '</div>
    <div class="col-xs-3">' . $sl_code . '</div>
    <div class="col-xs-3">' . $tl_code. '</div>
  </div>';
}

print '</div>';
$this->page_footer();
