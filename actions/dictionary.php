<?php
namespace Attogram;

# requires setup in config.php:  $config['depth']['dictionary'] = 4;

if( !isset($this->uri[1]) || !$this->uri[1] ) {
  // Please select Source Langauge Code 
  $this->error404();
}
if( !isset($this->uri[2]) || !$this->uri[2] ) {
  // Please select Target Language Code
  $this->error404();
}

$s_code = $this->uri[1];
$t_code = $this->uri[2];

$langs = get_languages($this->sqlite_database);

if( !isset($langs[$s_code]) ) {
  // Source Language Code Not Found
  $this->error404();
}
if( !isset($langs[$t_code]) ) {
  // Target Language Code Not Found
  $this->error404();
}

$title = $langs[$s_code] . ' to ' . $langs[$t_code] . ' Dictionary';

$this->page_header($title . ' - OTE 1.0.0-dev');
print '<div class="container"><h1>' . $title . '</h1>';

$d = get_dictionary( $s_code, $t_code, $this->sqlite_database);
$sep = ' = ';
foreach( $d as $i ) {
  print $i['s_word'] . $sep . $i['t_word'] . '<br />';
}


print '</div>';
$this->page_footer();
