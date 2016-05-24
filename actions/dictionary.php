<?php
namespace Attogram;

# requires setup in config.php:  $config['depth']['dictionary'] = 4;


$rel_url = $this->path . '/' . $this->uri[0] . '/';

if( sizeof($this->uri) == 2 ) {
    $this->page_header('Dictionary list - OTE v1.0.0-dev');
    print '<div class="container">';
    print '<h1>Dictionary list</h1>';
    $sql = 'SELECT DISTINCT s_code, t_code FROM word2word ORDER BY s_code, t_code';
    $r = $this->sqlite_database->query($sql);
    
    $langs = get_languages($this->sqlite_database);
    
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
    print '<h3><ul>';
    foreach( $dlist as $url=>$name ) {
      print '<li><a href="' . $url . '">' . $name . '</a></li>';
    }
    print '</ul></h3>';
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

$langs = get_languages($this->sqlite_database);

if( !isset($langs[$s_code]) ) {
  // Source Language Code Not Found
  header("Location: $rel_url");
}
if( !isset($langs[$t_code]) ) {
  // Target Language Code Not Found
  header("Location: $rel_url");
}

$title = $langs[$s_code] . ' to ' . $langs[$t_code] . ' Dictionary';

$this->page_header($title . ' - OTE 1.0.0-dev');
print '<div class="container"><h1>' . $title . '</h1><hr />';

$d = get_dictionary( $s_code, $t_code, $this->sqlite_database);
$sep = ' = ';
$prev = '';

foreach( $d as $i ) {
  if( $i['s_word'] != $prev && $prev != '') { print '<br /> '; }
  print $i['s_word'] . $sep . $i['t_word'] . '<br />';
  $prev = $i['s_word'];
}


print '<br /><hr /><br /></div>';
$this->page_footer();
