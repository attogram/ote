<?php
namespace Attogram;

$this->page_header('Import - OTE v1.0.0-dev');

$default['s'] = 'nl';
$default['t'] = 'en';
$default['d'] = '=';

if( $_POST ) {
  do_import($this->sqlite_database);
}
?>
<div class="container">
  <h1>Import Word Pairs</h1>
  
  <form action="." method="POST">
  
  <div class="col-xs-4">
    <label for="s">Source Language Code</label>
    <br />
    <input type="text" class="form-control" name="s" value="<?php print $default['s']; ?>">
  </div>

  <div class="col-xs-4">
    <label for="t">Target Language Code</label>
    <br />
    <input type="text" class="form-control" name="t" value="<?php print $default['t']; ?>">
  </div>

  <div class="col-xs-4">
    <label for="d">Deliminator</label>
    <br />
    <input type="text" class="form-control" name="d" value="<?php print $default['d']; ?>">
  </div>
  
  <div class="col-xs-12">
    <br />
    <label for="w">Word pairs:</label>
    <textarea class="form-control" name="w" rows="10"></textarea>
    <button type="submit" class="btn btn-primary"> &nbsp; &nbsp; Import Word Pairs &nbsp; &nbsp; </button>
  </div>
  
  </form>
</div>
<?php
$this->page_footer();

function do_import($db) {

  if( !isset($_POST['w']) || !$_POST['w'] ) {
    $error[] = 'error - no word pairs found';
  }
  if( !isset($_POST['d']) || !$_POST['d'] ) {
    $error[] = 'error - no deliminator set';
  }
  if( !isset($_POST['t']) || !$_POST['t'] ) {
    $error[] = 'error - no target language code set';
  }  
  if( !isset($_POST['s']) || !$_POST['s'] ) {
    $error[] = 'error - no source language code set';
  }   
  if( isset($error) ) {
    print '<div class="container"><p>' . implode( $error, '<br />' ) . '</p></div>';
    return;
  }
  
  $w = $_POST['w'];
  $d = $_POST['d'];
  $t = $_POST['t'];
  $s = $_POST['s'];

  $lines = explode("\n", $w);
  print '<div class="container">';
  print '<p>Source Language Code: ' . htmlentities($s) . '</p>';
  print '<p>Target Language Code: ' . htmlentities($t) . '</p>';
  print '<p>Deliminator: ' . htmlentities($d) . '<hr /></p>';
  print '<p>Lines: ' . sizeof($lines) . '</p>';
  ob_flush(); flush();
  
  $line_count = 0;
  $import_count = 0;
  
  foreach($lines as $line) {

    set_time_limit(240);

    $line_count++;
    $line = trim($line);
    
    if( $line == '' ) {
      //print '<p>Info: Line #' . $line_count . ': Blank line found. Skipping line</p>';
      continue;
    }
    
    if( preg_match('/^#/', $line) ) {
      //print '<p>Info: Line #' . $line_count . ': Comment line found. Skipping line.</p>';
      continue;
    }

    if( !preg_match('/' . $d . '/', $line) ) {
      print '<p>Error: Line #' . $line_count . ': Deliminator (' .htmlentities($d) . ') Not Found. Skipping line.</p>';
      continue;
    }

    $wp = explode($d, $line);

    if( sizeof($wp) != 2 ) {
      print '<p>Error: Line #' . $line_count . ': Malformed line.  Expecting 2 words, found ' . sizeof($wp) . ' words</p>';
      continue;
    }
    
    $sw = trim($wp[0]);
    if( !$sw ) {
      print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing source word</p>';
      continue;      
    }
    $tw = trim($wp[1]);
    if( !$tw ) {
      print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing target word</p>';
      continue;      
    }
    
    $si = get_id_from_word($sw, $db);
    $ti = get_id_from_word($tw, $db);
    
    $sql = 'INSERT INTO word2word (s_id, s_code, t_id, t_code) VALUES (:s_id, :s_code, :t_id, :t_code)';
    $bind = array( 's_id'=>$si, 's_code'=>$s, 't_id'=>$ti, 't_code'=>$t);
    $r = $db->queryb($sql, $bind);
    if( !$r ) {
      print '<p>Error: Line #' . $line_count . ': Can not insert word pair into database: '
      . print_r($db->db->errorInfo(),1) . '</p>';
    } else {
      $import_count++;
      print " $import_count "; 
    }

    ob_flush(); flush();

  }
  
  print '<p><hr />IMPORT DONE.</p>';
  print '<p>' . $import_count . ' word pairs imported.</p>';
  print '</div>';
}

function get_id_from_word($word, $db) {
  $sql = 'SELECT id FROM word WHERE word = :word';
  $bind=array('word'=>$word);
  $r = $db->query($sql, $bind);
  if( !$r || !isset($r[0]) || !isset($r[0]['id']) ) {
    //print '<p>ERROR: no word.id found.  Inserting word: ' . $word . '</p>';
    return insert_word($word, $db);
  }
  return $r[0]['id'];
}

function insert_word($word, $db) {
  $sql = 'INSERT INTO word (word) VALUES (:word)';
  $bind=array('word'=>$word);
  $r = $db->queryb($sql, $bind);
  if( !$r ) {
    print '<p>ERROR: can not insert word</p>';
    return 0;
  }
  return $db->db->lastInsertId();
}