<?php // Open Translation Engine - Dictionary Page v0.1.1
/*
 OTE Dictionary Page

 Requires config setup:
   $config['depth']['dictionary'] = 3;

 URL formats:

  dictionary/source_language_code/target_language_code/
    all translations, from source language, into target language

  dictionary/source_language_code//
    all translations, from source language, into any language

  dictionary///
  dictionary//
    all translations, from any language, into any language

  dictionary/
    list all dictionaries

*/
namespace Attogram;

$ote = new ote($this->db, $this->log);

$rel_url = $this->path . '/' . $this->uri[0] . '/';

if( sizeof($this->uri) == 1 ) { // list all dictionaries

    $dlist = $ote->get_dictionary_list();
    $this->page_header(sizeof($dlist) . ' Dictionaries');
    print '<div class="container"><h1>📚 <code>' . sizeof($dlist) . '</code> Dictionaries</h1><hr />';
    foreach( $dlist as $url=>$name ) {
      print '<p><a href="' . $url . '"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> ' . $name . '</a></p>';
    }
    print '</div>';
    $this->page_footer();
    exit;
}

$s_code = isset($this->uri[1]) ? $this->uri[1] : '';
$t_code = isset($this->uri[2]) ? $this->uri[2] : '';

if( $s_code && $t_code && ($s_code == $t_code) ) { // Error - Source and Target language code the same
  $this->error404('Source and Target language the same');
}

$langs = $ote->get_languages();

if( $s_code && !isset($langs[$s_code]) ) { // Source Language Code Not Found
  $this->error404('Source language not found yet');
}
if( $t_code && !isset($langs[$t_code]) ) { // Target Language Code Not Found
  $this->error404('Target language not found yet');
}

$s_name = isset($langs[$s_code]['name']) ? $langs[$s_code]['name'] : '*';
$t_name = isset($langs[$t_code]['name']) ? $langs[$t_code]['name'] : '*';
$s_id = isset($langs[$s_code]['id']) ? $langs[$s_code]['id'] : 0;
$t_id = isset($langs[$t_code]['id']) ? $langs[$t_code]['id'] : 0;

$title = $s_name . ' to ' . $t_name . ' Dictionary';
$this->page_header($title);

$d = $ote->get_dictionary( $s_id, $t_id );
print '<div class="container"><h1>📚 ' . $title . '</h1>';
print '<p><code>' . sizeof($d) . '</code> translations:</p>';

$sep = ' = ';
$prev = '';

foreach( $d as $i ) {
  if( $i['s_word'] != $prev && $prev != '') {
    //print '<br />';
  }
  $prev = $i['s_word'];

  print $ote->display_pair( $i['s_word'], $s_code,
                            $i['t_word'], $t_code,
                            $this->path, $sep, true, false );
}

print '</p></div>';
$this->page_footer();
