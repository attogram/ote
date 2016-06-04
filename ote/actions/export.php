<?php
/*
 OTE Export Page v0.0.8

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
    $this->page_header('Export list');
    print '<div class="container"><h1>Export list</h1>';
    $dlist = $ote->get_dictionary_list($rel_url);
    print '<p><code>' . sizeof($dlist) . '</code> Dictionaries:</p><ul>';
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

$title = 'Export ' . $langs[$s_code]['name'] . ' to ' . $langs[$t_code]['name'];
$this->page_header($title);

$result = $ote->get_dictionary( $langs[$s_code]['id'], $langs[$t_code]['id'] );

$sep = ' = ';

?>
<div class="container">
<h1><?php print $title; ?></h1>
<textarea class="form-control" rows="20" id="export">
# <?php print $langs[$s_code]['name']  . ' to ' . $langs[$t_code]['name']  . "\n"; ?>
# <?php print "$s_code to $t_code\n"; ?>
# <?php print sizeof($result) . " word pairs\n"; ?>
# <?php print "deliminator: $sep\n"; ?>
#
<?php
foreach( $result as $r ) {
  print $r['s_word'] . $sep . $r['t_word'] . "\n";
}
?></textarea>
</div>
<?php
$this->page_footer();
