<?php
/*
 OTE Word Page v0.0.5

 Requires config setup:
   $config['depth']['word'] = 4;

 URL formats:

  word/source_language_code/target_language_code/word
    translations for this word, from source language, into target language

  word/source_language_code//word
    translations for this word, from source language, into any language

  word///word
    translations for this word, from any language, into any language

  word/
    all words

*/
namespace Attogram;

if( sizeof($this->uri) == 1 ) { // Show All Words
  $ote = new ote($this->db, $this->log);
  $all = $ote->get_all_words();
  $title = 'Word list';
  $this->page_header($title);
  print '<div class="container"><h1>' . $title . '</h1><p><code>' . sizeof($all) . '</code> words:</p><ul>';
  foreach( $all as $w ) {
    print '<li><a href="' . $this->path . '/' . $this->uri[0] . '///'
    . urlencode($w['word']) . '">' . htmlentities($w['word']) . '</a></li>';
  }
  print '</ul></div>';
  $this->page_footer();
  exit;
}

// Check URI is OK
if( sizeof($this->uri) > 4 ) {
  $this->error404('No Swimming in the Deep End of the word');
}
if( sizeof($this->uri) < 4 ) {
  $this->error404('No Swimming in the Shallow End of the word');
}
if( !$this->uri[3] ) {
  $this->error404('The Word is the Bird.  Missing Bird.');
}

$ote = new ote($this->db, $this->log);
$langs = $ote->get_languages();

// Check Languages exist
$s_code = $this->uri[1];
if( $s_code && !isset($langs[$s_code]) ) {
  $this->error404('Source Language not found yet');
}
$t_code = $this->uri[2];
if( $t_code && !isset($langs[$t_code]) ) {
  $this->error404('Target Language not found yet');
}

$word = urldecode($this->uri[3]);

$this->log->debug("word.php: s_code=$s_code t_code=$t_code word=" . htmlentities($word));

$r = $ote->search_dictionary(
  $word,
  $ote->get_language_id_from_code($s_code),
  $ote->get_language_id_from_code($t_code) );

if( !$r ) {
  $this->log->error("word.php: No Translations Found");
  $this->error404('Nothing found but wordly emptiness');
}

$this->page_header('Word: ' . htmlentities($word) );
print '<div class="container">';
print '<h1><kbd><strong>' . htmlentities($word) . '</strong></kbd></h1>';

if( $s_code && $t_code ) {
  $header = '<strong>' . $langs[$s_code]['name'] . '</strong> (<code>' . $s_code . '</code>) to '
  . '<strong>' . $langs[$t_code]['name'] . '</strong> (<code>' . $t_code . '</code>)';
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

  //print '<pre>w=' . print_r($w,1) . '</pre>';

  $s_code = $w['sc'];
  $s_name = $w['sn'];
  $s_word = $w['s_word'];
  $t_code = $w['tc'];
  $t_name = $w['tn'];
  $t_word = $w['t_word'];

  $sub_header = $s_name . ' to ' . $t_name;

//  print "<pre>  word=$word \t\t\t sub_header=$sub_header
//s_word=$s_word \t s_code=$s_code \t s_name=$s_name
//t_word=$t_word \t t_code=$t_code \t t_name=$t_name</pre>";

  if( $sub_header != $prev_sub_header ) {
    print '<hr /><em>' . $sub_header . '</em><br />';
  }

  $base = $this->path . '/' . $this->uri[0];

  if( $s_code && $t_code ) {
    $s_word_url = $base . '///' . urlencode($s_word);
    $s_word_display = '<a href="' . $s_word_url . '">' . htmlentities($s_word) . '</a>';
  } elseif( $s_code && !$t_code ) {
    $s_word_url = $base . '/' . $s_code . '/' . $t_code . '/' . urlencode($s_word);
    $s_word_display = '<a href="' . $s_word_url . '">' . htmlentities($s_word) . '</a>';
  } else {
    $s_word_url = $base . '/' . $s_code . '//' . urlencode($s_word);
    $s_word_display = '<a href="' . $s_word_url . '">' . htmlentities($s_word) . '</a>';
  }

  $t_word_url = $base . '/' . $t_code . '//' . urlencode($t_word);
  $t_word_display = '<a href="' . $t_word_url . '">' . htmlentities($t_word) . '</a>';

  print "<strong>$s_word_display</strong> = $t_word_display<br />";

  $prev_sub_header = $sub_header;
}
print '</p>';
print '</div>';
$this->page_footer();
