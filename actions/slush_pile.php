<?php // Open Translation Engine - Slush Pile Page v0.0.2

namespace Attogram;
$title = '🛃 Slush Pile';
$this->page_header($title);

print '<div class="container"><h1 class="squished">' . $title . '</h1>';

$slush = $this->db->query('SELECT * FROM slush_pile ORDER BY id DESC');

// DEV todo - add pagination
// DEV todo - merge in display_pair

print '<p><code>' . sizeof($slush) . '</code> requests found:</p>';
foreach( $slush as $s ) {
  //print '<pre>' . print_r($s,1) . '</pre>';
  $date = isset($s['date']) ? htmlentities($s['date']) : '';
  $source_word = isset($s['source_word']) ? htmlentities($s['source_word']) : '';
  $source_word_url = '../word///' . urlencode($s['source_word']);
  $source_language_code = isset($s['source_language_code']) ? htmlentities($s['source_language_code']) : '';
  $target_word = isset($s['target_word']) ? htmlentities($s['target_word']) : '';
  $target_word_url = '../word///' . urlencode($s['target_word']);
  $target_language_code = isset($s['target_language_code']) ? htmlentities($s['target_language_code']) : '';
  print '<div class="row" style="border:solid 1px #ccc;padding:4px;">'
  . '<div class="col-sm-3">'  . $date . '</div>'
  . '<div class="col-sm-3"><a href="' . $source_word_url . '">' . $source_word . '</a></div>'
  . '<div class="col-sm-3"> = <a href="' . $target_word_url . '">' . $target_word . '</a></div>'
  . '<div class="col-sm-3">' . "<code>$source_language_code</code> = <code>$target_language_code</code></div>"
  . '</div>';
}

print '</div>';

$this->page_footer();
