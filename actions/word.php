<?php // Open Translation Engine - Word Page v0.1.8
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

  word/source_language_code/target_language_code/
      all words in source language with translations in target language

  word//target_language_code/
      all words with translations in target language

  word/source_language_code/
    all words in source language

  word/
    all words

*/

namespace Attogram;

$ote = new ote( $this->db, $this->log );

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

list( $limit, $offset ) = $this->db->get_set_limit_and_offset(
  $default_limit  = 1000,
  $default_offset = 0,
  $max_limit      = 5000,
  $min_limit      = 100
);

switch( sizeof($this->uri) ) { // Show Word lists
  case 1:
    show_all_words( $ote, $this, $limit, $offset );
    break;
  case 2:
    show_all_words( $ote, $this, $limit, $offset, $this->uri[1] );
    break;
  case 3:
    show_all_words( $ote, $this, $limit, $offset, $this->uri[1], $this->uri[2] );
    break;
  case 4:
    if( !$this->uri[3] ) {
      $this->error404('The Word is the Bird.  Missing Bird.');
    }
    break;
}

if( sizeof($this->uri) > 4 ) { // Check URI is OK
  $this->error404('No Swimming in the Deep End of the word');
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
  $put_s = false;
  $put_t = false;
} elseif( $s_code && !$t_code) {
  $header = '<strong>' . $langs[$s_code]['name'] . '</strong> (<code>' . $s_code . '</code>)';
  $put_s = true;
  $put_t = true;
} else {
  $header = '<code>ALL</code>';
  $put_s = true;
  $put_t = false;
}
print '<br /><p class="text-muted">language: ' . $header . '</p>';
print '<p class="text-muted"><code>' . sizeof($r) . '</code> translations:</p>';

foreach( $r as $w ) {
  print $ote->display_pair(
    $w['s_word'], // The Source Word
    $w['sc'],     // The Source Language Code
    $w['t_word'], // The Target Word
    $w['tc'],     // The Target Language Code
    $this->path,  // URL path, defaults to ''
    ' = ',        //  The Deliminator, defaults to ' = '
    $put_s,       // Put Language Source Code in word URLS, defaults to true
    $put_t        // Put Language Target Code in word URLs, defaults to false
  );
}

print '</div>';
$this->page_footer();


function show_all_words( $ote, $attogram, $limit, $offset, $scode = 0, $tcode = 0 )
{
  if( !$scode && !$tcode ) {
    $all_count = $ote->get_word_count();
    $all = $ote->get_all_words( $limit, $offset );
    $title = 'All Words';
  } elseif( $scode && !$tcode ) {
    $all_count = $ote->get_word_count( $ote->get_language_id_from_code($scode) );
    $all = $ote->get_all_words( $limit, $offset, $ote->get_language_id_from_code($scode) );
    $title = $ote->get_language_name_from_code($scode) . ' Words';
  } elseif( !$scode && $tcode ) {
    $all_count = $ote->get_word_count('', $ote->get_language_id_from_code($tcode) );
    $all = $ote->get_all_words( $limit, $offset, '', $ote->get_language_id_from_code($tcode) );
    $title =  'Words with translations into ' . $ote->get_language_name_from_code($tcode);
  } elseif( $scode && $tcode ) {
    $all_count = $ote->get_word_count( $ote->get_language_id_from_code($scode), $ote->get_language_id_from_code($tcode) );
    $all = $ote->get_all_words( $limit, $offset, $ote->get_language_id_from_code($scode), $ote->get_language_id_from_code($tcode) );
    $title = $ote->get_language_name_from_code($scode) . ' Words with translations into ' . $ote->get_language_name_from_code($tcode);
  }

  $attogram->page_header('🔤 ' . $title);
  print '<div class="container"><h1>🔤 ' . $title . '</h1>';
  print $attogram->db->pager( $all_count, $limit, $offset );
  print '<style>a { color:inherit; }</style><h3>';
  foreach( $all as $w ) {
    print '<a href="' . $attogram->path . '/' . $attogram->uri[0] . '///' . urlencode($w['word']) . '">' . htmlentities($w['word']) . '</a>, ';
  }
  print '</h3></div>';
  print $attogram->db->pager( $all_count, $limit, $offset );
  $attogram->page_footer();
  exit;
} // end function show_all_words()
