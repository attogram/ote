<?php // Open Translation Engine - Slush Pile Page v0.0.6

namespace Attogram;

$title = '🛃 Slush Pile';

$this->page_header($title);
print '<div class="container"><h1 class="squished">' . $title . '</h1>';

$ote = new ote( $this->db, $this->log );

list( $limit, $offset ) = $ote->db->get_set_limit_and_offset(
  $default_limit  = 15,
  $default_offset = 0,
  $max_limit      = 1000,
  $min_limit      = 5
);

$sql = 'SELECT * FROM slush_pile ORDER BY id DESC LIMIT ' . $limit;
if( $offset ) {
  $sql .= ", $offset";
}
$slush = $this->db->query($sql);

print $this->db->pager(
  $ote->get_count_slush_pile(),
  $limit,
  $offset,
  $prepend_query_string = ''
);

foreach( $slush as $s ) {
  //print '<pre>' . print_r($s,1) . '</pre>';
  $date = isset($s['date']) ? htmlentities($s['date']) : '';
  $type = isset($s['type']) ? htmlentities($s['type']) : '';
  $user_id = isset($s['user_id']) ? htmlentities($s['user_id']) : '';
  $source_word = isset($s['source_word']) ? htmlentities($s['source_word']) : '';
  $source_word_url = '../word///' . urlencode($s['source_word']);
  $source_language_code = isset($s['source_language_code']) ? htmlentities($s['source_language_code']) : '';
  $target_word = isset($s['target_word']) ? htmlentities($s['target_word']) : '';
  $target_word_url = '../word///' . urlencode($s['target_word']);
  $target_language_code = isset($s['target_language_code']) ? htmlentities($s['target_language_code']) : '';
  print '<div class="row" style="border:solid 1px #ccc;padding:4px;">'
  . '<div class="col-sm-4">🕑:'  . $date . ' 👤:' . $user_id . ' 🔀:<strong>' . $type . '</strong></div>'
  . '<div class="col-sm-3"><a href="' . $source_word_url . '">' . $source_word . '</a></div>'
  . '<div class="col-sm-3"> = <a href="' . $target_word_url . '">' . $target_word . '</a></div>'
  . '<div class="col-sm-2">' . "<code>$source_language_code</code> = <code>$target_language_code</code></div>"
  . '</div>';
}

print '</div>';

$this->page_footer();
