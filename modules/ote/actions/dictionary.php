<?php
/*
 OTE Dictionary Page v0.0.1

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

$ote = new ote($this->db);

$rel_url = $this->path . '/' . $this->uri[0] . '/';

if( sizeof($this->uri) == 2 ) { // list all dictionaries
    $this->page_header('Dictionary list');
    print '<div class="container"><h1>Dictionary list</h1>';
    $dlist = $ote->get_dictionary_list($rel_url);
    print '<p><code>' . sizeof($dlist) . '</code> Dictionaries:</p><ul>';
    foreach( $dlist as $url=>$name ) {
      print '<li><a href="' . $url . '">' . $name . '</a></li>';
    }
    print '</ul></div>';
    $this->page_footer();
    exit;
}

if( !isset($this->uri[1]) || !$this->uri[1] ) { // Please select Source Langauge Code
  header("Location: $rel_url");
}
if( !isset($this->uri[2]) || !$this->uri[2] ) {  // Please select Target Language Code
  header("Location: $rel_url");
}

$s_code = $this->uri[1];
$t_code = $this->uri[2];

if( $s_code == $t_code ) { // Error - Source and Target language code the same
  header("Location: $rel_url");
}

$langs = $ote->get_languages();

if( !isset($langs[$s_code]) ) { // Source Language Code Not Found
  header("Location: $rel_url");
}
if( !isset($langs[$t_code]) ) { // Target Language Code Not Found
  header("Location: $rel_url");
}

$title = $langs[$s_code] . ' to ' . $langs[$t_code] . ' Dictionary';

$this->page_header($title);
print '<div class="container">';
print '<h1>' . $title . '</h1>';
$d = $ote->get_dictionary( $s_code, $t_code);
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
