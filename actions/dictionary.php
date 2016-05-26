<?php
/*
 OTE Dictionary Page

 Requires config setup:
   $config['depth']['dictionary'] = 4;

 URL formats:

  dictionary/source_language_code/target_language_code/
    all translations, from source language, into target language

  dictionary/source_language_code/
    all translations, from source language, into any language

  dictionary/
    list all dictionaries

*/
namespace Attogram;

$rel_url = $this->path . '/' . $this->uri[0] . '/';

$langs = get_languages($this->sqlite_database);

if( sizeof($this->uri) == 2 ) {
    $this->page_header('Dictionary list - OTE v1.0.0-dev');
    print '<div class="container">';
    print '<h1>Dictionary list</h1>';
    $sql = 'SELECT DISTINCT s_code, t_code FROM word2word ORDER BY s_code, t_code';
    $r = $this->sqlite_database->query($sql);
    
    
    $dlist = array();
    foreach( $r as $d ) {
      $url = $rel_url . $d['s_code'] . '/' . $d['t_code'] . '/';
      $dlist[ $url ] = $langs[ $d['s_code'] ] . ' to ' . $langs[ $d['t_code'] ]; 
      $r_url = $rel_url . $d['t_code'] . '/' . $d['s_code'] . '/'; 
      if( !array_key_exists($r_url,$dlist) ) {
          $dlist[ $r_url ] = $langs[ $d['t_code'] ] . ' to ' . $langs[ $d['s_code'] ]; 
      }
    }
    asort($dlist);
    
    print '<p><code>' . sizeof($dlist) . '</code> Dictionaries:</p>';
    print '<ul>';
    foreach( $dlist as $url=>$name ) {
      print '<li><a href="' . $url . '">' . $name . '</a></li>';
    }
    print '</ul>';
    print '</div>';   
    $this->page_footer();
    exit;
}

if( !isset($this->uri[1]) || !$this->uri[1] ) {
  // Please select Source Langauge Code 
  header("Location: $rel_url");
}
if( !isset($this->uri[2]) || !$this->uri[2] ) {
  // Please select Target Language Code
  header("Location: $rel_url");
}

$s_code = $this->uri[1];
$t_code = $this->uri[2];

if( $s_code == $t_code ) {
  // Error - Source and Target language code the same
  header("Location: $rel_url");  
}
if( !isset($langs[$s_code]) ) {
  // Source Language Code Not Found
  header("Location: $rel_url");
}
if( !isset($langs[$t_code]) ) {
  // Target Language Code Not Found
  header("Location: $rel_url");
}

$title = $langs[$s_code] . ' to ' . $langs[$t_code] . ' Dictionary';

$this->page_header($title);
print '<div class="container">';
print '<h1>' . $title . '</h1>';
$d = get_dictionary( $s_code, $t_code, $this->sqlite_database);
print '<p><code>' . sizeof($d) . '</code> translations:</p>';

print '<hr /><p>';

$sep = ' = ';
$prev = '';

foreach( $d as $i ) {
  if( $i['s_word'] != $prev && $prev != '') { print '<br />'; }
  print '<strong>'
  . '<a href="' . $this->path . '/word/' . $s_code . '//' . urlencode($i['s_word']) . '">' 
  . $i['s_word'] 
  . '</a>'
  . '</strong>' 
  . $sep
  . '<a href="' . $this->path . '/word/' . $t_code . '//' . urlencode($i['t_word']) . '">' 
  . $i['t_word'] 
  . '</a>'
  . '<br />';
  $prev = $i['s_word'];
}


print '</p></div>';
$this->page_footer();
