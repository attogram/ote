<?php
namespace Attogram;

$this->page_header('Import - OTE v1.0.0-dev');

$default['s'] = '';
$default['t'] = '';
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
  
  $w = $_POST['w']; // List of word pairs

  $d = $_POST['d']; // Deliminator
  $d = str_replace('\t', "\t", $d); // allow real tabs

  $s = trim($_POST['s']); // Source Language Code
  $sn = get_language_name_from_code($s, $db);

  $t = trim($_POST['t']); // Target Language Code
  $tn = get_language_name_from_code($t, $db);
  
  $lines = explode("\n", $w);
  print '<div class="container">';
  print 'Source Language: Code:' . htmlentities($s) . ' Name:' . htmlentities($sn) . '<br />';
  print 'Target Language: Code:' . htmlentities($t) . ' Name:' . htmlentities($tn) . '<br />';
  print 'Deliminator: ' . htmlentities($d) . '<br />';
  print 'Lines: ' . sizeof($lines) . '<hr />';
  ob_flush(); flush();
  
  $line_count = 0;
  $import_count = 0;
  $error_count = 0;
  $skip_count = 0;
  $dupe_count = 0;
  
  foreach($lines as $line) {

    set_time_limit(240);

    $line_count++;
    $line = trim($line);
    
    if( $line == '' ) {
      //print '<p>Info: Line #' . $line_count . ': Blank line found. Skipping line</p>';
      $skip_count++;
      continue;
    }
    
    if( preg_match('/^#/', $line) ) {
      //print '<p>Info: Line #' . $line_count . ': Comment line found. Skipping line.</p>';
      $skip_count++;
      continue;
    }

    if( !preg_match('/' . $d . '/', $line) ) {
      print '<p>Error: Line #' . $line_count . ': Deliminator (' .htmlentities($d) . ') Not Found. Skipping line.</p>';
      $error_count++; $skip_count++;
      continue;
    }

    $wp = explode($d, $line);

    if( sizeof($wp) != 2 ) {
      print '<p>Error: Line #' . $line_count . ': Malformed line.  Expecting 2 words, found ' . sizeof($wp) . ' words</p>';
      $error_count++; $skip_count++;
      continue;
    }
    
    $sw = trim($wp[0]);
    if( !$sw ) {
      print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing source word</p>';
      $error_count++; $skip_count++;
      continue;      
    }
    $tw = trim($wp[1]);
    if( !$tw ) {
      print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing target word</p>';
      $error_count++; $skip_count++;
      continue;      
    }
    
    $si = get_id_from_word($sw, $db);
    $ti = get_id_from_word($tw, $db);
    
    $sql = 'INSERT INTO word2word (s_id, s_code, t_id, t_code) VALUES (:s_id, :s_code, :t_id, :t_code)';
    $bind = array( 's_id'=>$si, 's_code'=>$s, 't_id'=>$ti, 't_code'=>$t);
    $r = $db->queryb($sql, $bind);
    if( !$r ) {
      if( $db->db->errorCode() == '0000' ) {
        //print '<p>Info: Line #' . $line_count . ': Already Exists.  Skipping line';
        $dupe_count++; $skip_count++;
        continue;
      }
      print '<p>Error: Line #' . $line_count . ': Database Insert Error.'
      . ' sw: ' . htmlentities($sw)
      . ' tw: ' . htmlentities($tw)
      . ' Bind: ' . print_r($bind,1) 
      . ' errorInfo: ' . print_r($db->db->errorInfo(),1) 
      . '</p>';
      $error_count++; $skip_count++;
    } else {
      $import_count++;
      print " $import_count "; 
    }

    ob_flush(); flush();

  } // end foreach line
  
  print '<hr />';
  print $import_count . ' word pairs imported.<br />';
  print $error_count . ' errors.<br />';
  print $skip_count . ' lines skipped.<br />';
  print $dupe_count . ' duplicate lines.<br />';
  print '</div>';

} // end do_import
