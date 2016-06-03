<?php
/*
 OTE Word Page v0.0.4

 Requires config setup:
   $config['depth']['word'] = 4;

 URL formats:

  word/source_language_code/target_language_code/word
    translations for this word, from source language, into target language

  word/source_language_code//word
    translations for this word, from source language, into any language

  word///word
    translations for this word, from any language, into any language

  word/source_language_code/
    all words in this language

  word/
    all words

*/
namespace Attogram;

$ote = new ote($this->db, $this->log);

if( sizeof($this->uri) == 1 ) { // Show All Words
  $all = $ote->get_all_words();
  $title = 'Word list';
  $this->page_header($title);
  print '<div class="container">';
  print '<h1>' . $title . '</h1>';
  print '<p><code>' . sizeof($all) . '</code> words:</p>';
  print '<ul>';
  foreach( $all as $w ) {
    print '<li><a href="' . $this->path . '/' . $this->uri[0] . '///'
    . urlencode($w['word']) . '">' . $w['word'] . '</a></li>';
  }
  print '</ul>';
  print '</div>';
  $this->page_footer();
  exit;
}

if( sizeof($this->uri) != 4 ) {
  $this->error404();
}

$word = urldecode($this->uri[3]);
if( !$word ) {
  $this->error404();
}

$langs = $ote->get_languages();

$s_code = $this->uri[1];
if( $s_code && !isset($langs[$s_code]) ) {
  $this->error404();
}

$t_code = $this->uri[2];
if( $t_code && !isset($langs[$t_code]) ) {
  $this->error404();
}

if( $t_code && !$s_code ) {
  $this->error404();
}

$r = $ote->get_translation($word, $s_code, $t_code);

if( !$r ) {
  $this->error404();
}

$this->page_header('Word');
print '<div class="container">';
print '<h1><kbd><strong>' . htmlentities($word) . '</strong></kbd></h1>';

if( $s_code && $t_code ) {
  $header = '<strong>' . $langs[$s_code]['name'] . '</strong> (<code>' . $s_code . '</code>) to '
  . '<strong>' . $langs[$t_code] . '</strong> (<code>' . $t_code . '</code>)';
} elseif( $s_code && !$t_code) {
  $header = '<strong>' . $langs[$s_code]['name'] . '</strong> (<code>' . $s_code . '</code>)';
} else {
  $header = '<code>ALL</code>';
}
print '<br /><p class="text-muted">language: ' . $header . '</p>';
print '<p class="text-muted"><code>' . sizeof($r) . '</code> translations:</p>';

$sub_header = $prev_sub_header = '';
print '<p>';
foreach( $r as $w ) {
  if( !$s_code ) {
    $s_code = $ote->get_language_code_from_id( $w['sl'] );
  }
  if( !$t_code ) {
    $t_code = $ote->get_language_code_from_id( $w['tl'] );
  }
  $sub_header = $langs[$s_code]['name'] . ' to ' . $langs[$t_code]['name'];

  if( $sub_header != $prev_sub_header ) {
    print '<hr /><em>' . $sub_header . '</em><br />';
  }

  $base = $this->path . '/' . $this->uri[0];

  if( $s_code && $t_code ) {
    $s_word_url = $base . '///' . urlencode($w['s_word']);
    $s_word_display = '<a href="' . $s_word_url . '">' . $w['s_word'] . '</a>';
  } elseif( $s_code && !$t_code ) {
    $s_word_url = $base . '/' . $s_code . '/' . $t_code . '/' . urlencode($w['s_word']);
    $s_word_display = '<a href="' . $s_word_url . '">' . $w['s_word'] . '</a>';
  } else {
    $s_word_url = $base . '/' . $s_code . '//' . urlencode($w['s_word']);
    $s_word_display = '<a href="' . $s_word_url . '">' . $w['s_word'] . '</a>';
  }

  $t_word_url = $base . '/' . $t_code . '//' . urlencode($w['t_word']);
  $t_word_display = '<a href="' . $t_word_url . '">' . $w['t_word'] . '</a>';

  print "<strong>$s_word_display</strong> = $t_word_display<br />";

  $prev_sub_header = $sub_header;
}
print '</p>';
print '</div>';
$this->page_footer();
