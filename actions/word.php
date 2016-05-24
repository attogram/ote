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

if( sizeof($this->uri) == 2 ) {
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

$sql .= "$and ORDER BY sw.word, tw.word";
$r_sql .= "$r_and ORDER BY sw.word, tw.word";

$r = $this->sqlite_database->query($sql, $bind);
$r_r = $this->sqlite_database->query($r_sql, $bind);
/*
print "<pre>"
. "-- sql: $sql"
. "<br />-- bind: " . print_r($bind,1)
. "<br />-- #r: " . sizeof($r)
. '<br />-- r: ' . print_r($r,1)
. '<hr />'
. "<br />-- r_sql: $r_sql"
. "<br />-- bind: " . print_r($bind,1)
. "<br />-- #r_r: " . sizeof($r_r)
. '<br />-- r_r: ' . print_r($r_r,1)
. "</pre>";
*/
$r = array_merge($r,$r_r);

if( !$r ) {
  $this->error404();
}

$this->page_header('Word');
print '<div class="container">';
/*
print '<p>s_code: <code>' . $s_code . '</code></p>';
print '<p>t_code: <code>' . $t_code . '</code></p>';
print '<p>word: <code>' . htmlentities($word) . '</code></p>';
print '<p>langs: <code>' . print_r($langs,1) . '</code></p>';
*/
print '<p>' . sizeof($r) . ' translations found</p>';
foreach( $r as $w ) {
  print '<pre>'
  . $langs[ $w['s_code'] ] . ' = ' . $langs[ $w['t_code'] ]
  . '<br />'
  . $w['s_word'] . ' = ' . $w['t_word']
  . '</pre>';
}
print '</div>';
$this->page_footer();
