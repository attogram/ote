<?php // Open Translation Engine - Histoy Page v0.0.1

namespace Attogram;

$ote = new ote($this->db, $this->log);

$this->page_header('Search History');

print '<div class="container"><h1>🔭 Search History</h1>';

$sql = 'SELECT * FROM history ORDER BY date DESC, count DESC';
$hr = $this->db->query($sql);
print '<p>DATE: COUNT: SL: TL: WORD</p>';
foreach( $hr as $h ) {
  $url = $this->path . '/word///' . htmlentities($h['word']);
  $sl_code = $ote->get_language_code_from_id($h['sl']);
  if( !$sl_code ) {
    $sl_code = '*';
  }
  $tl_code = $ote->get_language_code_from_id($h['tl']);
  if( !$tl_code ) {
    $tl_code = '*';
  }
  print '<p>'
  . $h['date'] . ': '
  . $h['count'] . ': '
  . $sl_code . ': '
  . $tl_code . ': '
  . '<a href="' . $url . '">' . $h['word'] . '</a>'
  . '</p>';
}

print '</div>';
$this->page_footer();
