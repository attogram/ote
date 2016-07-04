<?php // Open Translation Engine - Dictionary Page v0.2.1
/*
 OTE Dictionary Page

 Requires config setup:
   $config['depth']['dictionary'] = 3;

 URL formats:

  dictionary/source_language_code/target_language_code/
    all translations, from source language, into target language

  dictionary//target_language_code/
    all translations, from any language, into target language

  dictionary/source_language_code//
    all translations, from source language, into any language

  dictionary///
  dictionary//
    all translations, from any language, into any language

  dictionary/
    list all dictionaries

*/
namespace Attogram;

$ote = new ote( $this );

$rel_url = $this->path . '/' . urlencode($this->uri[0]) . '/';

if( sizeof($this->uri) == 1 ) { // list all dictionaries

    $dlist = $ote->get_dictionary_list();
    $this->page_header('📚 ' . sizeof($dlist) . ' Dictionaries');
    print '<div class="container"><h1 class="squished">📚 <code>' . sizeof($dlist) . '</code> Dictionaries</h1><hr />';
    foreach( $dlist as $url => $name ) {
      print '<p><a href="' . $url . '"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> ' . $name . '</a></p>';
    }
    print '</div>';
    $this->page_footer();
    exit;
}

$s_code = isset($this->uri[1]) ? urldecode($this->uri[1]) : '';
$t_code = isset($this->uri[2]) ? urldecode($this->uri[2]) : '';

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

list( $limit, $offset ) = $this->db->get_set_limit_and_offset(
  $default_limit  = 250,
  $default_offset = 0,
  $max_limit      = 1000,
  $min_limit      = 10
);

$s_name = isset($langs[$s_code]['name']) ? $langs[$s_code]['name'] : '*';
$t_name = isset($langs[$t_code]['name']) ? $langs[$t_code]['name'] : '*';
$s_id   = isset($langs[$s_code]['id'])   ? $langs[$s_code]['id']   : 0;
$t_id   = isset($langs[$t_code]['id'])   ? $langs[$t_code]['id']   : 0;

$title = $s_name . ' to ' . $t_name . ' Dictionary';
$this->page_header($title);
print '<div class="container"><h1 class="squished">📚 ' . $title . '</h1>';

$d_all = $ote->get_dictionary_translations_count( $s_id, $t_id );
$d = $ote->get_dictionary( $s_id, $t_id, $limit, $offset );

print $this->db->pager( $d_all, $limit, $offset );

foreach( $d as $i ) {
  print $ote->display_pair(
    $i['s_word'], //* @param  string  $sw   The Source Word
    $i['sc'], //* @param  string  $sc   The Source Language Code
    $i['t_word'], //* @param  string  $tw   The Target Word
    $i['tc'], //* @param  string  $tc   The Target Language Code
    $this->path, //* @param  string  $path (optional) URL path, defaults to ''
    ' = ', //* @param  string  $d    (optional) The Deliminator, defaults to ' = '
    true, //* @param  bool    $usc  (optional) Put Language Source Code in word URLS, defaults to true
    false //* @param  bool    $utc  (optional) Put Language Target Code in word URLs, defaults to false
  );
}

print '</p></div>';
$this->page_footer();
