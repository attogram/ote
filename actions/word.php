<?php
/*
 OTE Word Page

 Requires setup in config.php:
   $config['depth']['word'] = 5;

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

if( sizeof($this->uri) == 2 ) { // Show All Words
  $sql = 'SELECT word FROM word ORDER BY word';
  $all = $this->sqlite_database->query($sql);
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

if( sizeof($this->uri) != 5 ) {
  $this->error404();
}

$word = urldecode($this->uri[3]);
if( !$word ) {
  $this->error404();
}

$langs = get_languages($this->sqlite_database);

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

$and = $r_and = '';
$bind = array();
$sql = '
SELECT sw.word AS s_word, word2word.s_code, tw.word AS t_word, word2word.t_code
FROM word2word, word AS sw, word AS tw
WHERE sw.word = :word AND sw.id = word2word.s_id AND tw.id = word2word.t_id
';
$r_sql = '
SELECT sw.word AS t_word, word2word.t_code AS s_code, tw.word AS s_word, word2word.s_code AS t_code
FROM word2word, word AS sw, word AS tw
WHERE tw.word = :word AND sw.id = word2word.s_id AND tw.id = word2word.t_id
';
$bind['word'] = $word;

if( $s_code && $t_code ) {
  $and = 'AND word2word.s_code = :s_code AND word2word.t_code = :t_code';
  $r_and = 'AND word2word.s_code = :t_code AND word2word.t_code = :s_code';
  $bind['s_code']=$s_code; $bind['t_code']=$t_code;
} elseif ( $s_code && !$t_code ) {
  $and = 'AND word2word.s_code = :s_code';
  $r_and = 'AND word2word.t_code = :s_code';
  $bind['s_code']=$s_code;
}

$order = '';  // dev
$sql .= "$and $order";
$r_sql .= "$r_and $order";

$r = $this->sqlite_database->query($sql, $bind);
$r_r = $this->sqlite_database->query($r_sql, $bind);

$r = array_merge($r,$r_r);

$r = multiSort($r, 's_code', 't_code', 's_word', 't_word');

if( !$r ) {
  $this->error404();
}

$this->page_header('Word');
print '<div class="container">';
print '<h1><kbd><strong>' . htmlentities($word) . '</strong></kbd></h1>';

if( $s_code && $t_code ) {
  $header = '<strong>' . $langs[$s_code] . '</strong> (<code>' . $s_code . '</code>) to ' 
  . '<strong>' . $langs[$t_code] . '</strong> (<code>' . $t_code . '</code>)';
} elseif( $s_code && !$t_code) {
  $header = '<strong>' . $langs[$s_code] . '</strong> (<code>' . $s_code . '</code>)';
} else {
  $header = '<code>*</code>';
}
print '<br /><p class="text-muted">language: ' . $header . '</p>';  
print '<p class="text-muted"><code>' . sizeof($r) . '</code> translations found</p>';

$sub_header = $prev_sub_header = '';
print '<p>';
foreach( $r as $w ) {

  $sub_header = $langs[ $w['s_code'] ] . ' to ' . $langs[ $w['t_code'] ];

  if( $sub_header != $prev_sub_header ) {
    print '<hr /><em>' . $sub_header . '</em><br />';
  }

  print '<strong>' . $w['s_word'] . '</strong>' . ' = ' . $w['t_word'] . '<br />';
  
  $prev_sub_header = $sub_header;
}
print '</p>';
print '</div>';
$this->page_footer();
