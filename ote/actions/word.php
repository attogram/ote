<?php // Open Translation Engine - Word Page v0.1.0
/*
 OTE Word Page

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

if( isset($_GET['l']) && $_GET['l'] ) { // LIMIT
  $limit = (int)$_GET['l'];
  if( $limit < 100 ) {
    $this->error404('No small limits');
  }
  if( $limit > 5000 ) {
    $this->error404('No big limits');
  }
  if( isset($_GET['o']) && $_GET['o'] ) { // OFFSET
    $offset = (int)$_GET['o'];
  } else {
    $offset = 0;
  }
} else {
  $limit = 1000;
  $offset = 0;
}

if( sizeof($this->uri) == 1 ) { // Show All Words
  $ote = new ote($this->db, $this->log);
  $all_count = $ote->get_word_count();
  $title = 'Words';
  $this->page_header($title);
  print '<div class="container"><h1>🔤 ' . $title . '</h1>';
  print pager( $all_count, $limit, $offset );

  $all = $ote->get_all_words( $limit, $offset );
  print '<style>
  a { color: inherit; }
  </style><h3>';
  foreach( $all as $w ) {
    print '<a href="' . $this->path . '/' . $this->uri[0] . '///'
    . urlencode($w['word']) . '">' . htmlentities($w['word']) . '</a>, ';
  }
  print '</h3></div>';
  print pager( $all_count, $limit, $offset );
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

print '<div style="font-size:45pt;">' . htmlentities($word) . '</div>';

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

foreach( $r as $w ) {
  $s_code = $w['sc'];
  $s_name = $w['sn'];
  $s_word = $w['s_word'];
  $t_code = $w['tc'];
  $t_name = $w['tn'];
  $t_word = $w['t_word'];
  $base = $this->path . '/' . $this->uri[0];
  if( $s_code && $t_code ) {
    print $ote->display_pair(
      $s_word, // * @param  string  $sw   The Source Word
      $s_code, // * @param  string  $sc   The Source Language Code
      $t_word, // * @param  string  $tw   The Target Word
      $t_code, // * @param  string  $tc   The Target Language Code
      $this->path, // * @param  string  $path (optional) URL path, defaults to ''
      ' = ', // * @param  string  $d    (optional) The Deliminator, defaults to ' = '
      false, // * @param  bool    $usc  (optional) Put Language Source Code in word URLS, defaults to true
      false // * @param  bool    $utc  (optional) Put Language Target Code in word URLs, defaults to false
    );
  } elseif( $s_code && !$t_code ) {
    print $ote->display_pair(
      $s_word, // * @param  string  $sw   The Source Word
      $s_code, // * @param  string  $sc   The Source Language Code
      $t_word, // * @param  string  $tw   The Target Word
      $t_code, // * @param  string  $tc   The Target Language Code
      $this->path, // * @param  string  $path (optional) URL path, defaults to ''
      ' = ', // * @param  string  $d    (optional) The Deliminator, defaults to ' = '
      true, // * @param  bool    $usc  (optional) Put Language Source Code in word URLS, defaults to true
      true // * @param  bool    $utc  (optional) Put Language Target Code in word URLs, defaults to false
    );
  } else {
    print $ote->display_pair(
      $s_word, // * @param  string  $sw   The Source Word
      $s_code, // * @param  string  $sc   The Source Language Code
      $t_word, // * @param  string  $tw   The Target Word
      $t_code, // * @param  string  $tc   The Target Language Code
      $this->path, // * @param  string  $path (optional) URL path, defaults to ''
      ' = ', // * @param  string  $d    (optional) The Deliminator, defaults to ' = '
      true, // * @param  bool    $usc  (optional) Put Language Source Code in word URLS, defaults to true
      false // * @param  bool    $utc  (optional) Put Language Target Code in word URLs, defaults to false
    );
  }
}

print '</div>';
$this->page_footer();
