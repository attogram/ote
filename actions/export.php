<?php
/*
 OTE Export Page

 Requires config setup:
   $config['depth']['export'] = 4;

 URL formats:

  export/source_language_code/target_language_code/
    export all translations, from source language, into target language

  export/source_language_code/
    export all translations, from source language, into any language

  export/
    list all dictionaries

*/
namespace Attogram;

$rel_url = $this->path . '/' . $this->uri[0] . '/';

if( sizeof($this->uri) == 2 ) { // list all exportable dictionaries
    $this->page_header('Export list');
    print '<div class="container"><h1>Export list</h1>';
    $dlist = get_dictionary_list($this->sqlite_database, $rel_url);
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

$langs = get_languages($this->sqlite_database);

if( !isset($langs[$s_code]) ) { // Source Language Code Not Found
  header("Location: $rel_url");
}
if( !isset($langs[$t_code]) ) { // Target Language Code Not Found
  header("Location: $rel_url");
}

$title = 'Export ' . $langs[$s_code] . ' to ' . $langs[$t_code];
$this->page_header($title);

$sql = '
SELECT sw.word AS s_word, tw.word AS t_word
FROM word2word AS ww, word AS sw, word AS tw
WHERE sw.id = ww.s_id
AND tw.id = ww.t_id
AND ww.s_code = :s_code
AND ww.t_code = :t_code
';
$bind = array( 's_code'=>$s_code, 't_code'=>$t_code );
$result = $this->sqlite_database->query($sql, $bind);

// todo: reverse lookup

$sep = ' = ';

?>
<div class="container">
<h1><?php print $title; ?></h1>
<textarea class="form-control" rows="10" id="export">
# <?php print $title . "\n"; ?>
<?php
foreach( $result as $r ) {
  print $r['s_word'] . $sep . $r['t_word'] . "\n";
}
print "\n# TODO: reverse lookup\n";
?></textarea>
</div>
<?php
$this->page_footer();
