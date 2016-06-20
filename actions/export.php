<?php // Open Translation Engine - Export Page v0.1.0
/*
 OTE Export Page

 Requires config setup:
   $config['depth']['export'] = 3;

 URL formats:

  export/source_language_code/target_language_code/
    export all translations, from source language, into target language

  export/source_language_code/
    export all translations, from source language, into any language

  export/
    list all dictionaries

*/
namespace Attogram;

$ote = new ote($this->db, $this->log);

$rel_url = $this->path . '/' . $this->uri[0] . '/';

if( sizeof($this->uri) == 1 ) { // list all exportable dictionaries
    $this->page_header('Export Translations');
    print '<div class="container"><h1>📤 Export Translations</h1>';
    $dlist = $ote->get_dictionary_list();
    print '<p><code>' . sizeof($dlist) . '</code> exportable Dictionaries:</p><ul>';
    foreach( $dlist as $url=>$name ) {
      print '<li><a href="' . $url . '">' . $name . '</a></li>';
    }
    print '</ul></div>';
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

$result = $ote->get_dictionary( $langs[$s_code]['id'], $langs[$t_code]['id'] );

$sep = ' = ';

header('Content-Type: text/plain');

print '# ' . $langs[$s_code]['name']  . ' to ' . $langs[$t_code]['name']  . " \n";
print "# ($s_code to $t_code)\n";
print "#\n";
print '# translations: ' . sizeof($result) . "\n";
print "# deliminator: $sep\n";
print "#\n";
print "# export from: " . $this->get_site_url() . "/\n";
print '# export time: ' . gmdate('r') . " UTC\n";
print "#\n";
foreach( $result as $r ) {
  print $r['s_word'] . $sep . $r['t_word'] . "\n";
}
exit;
