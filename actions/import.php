<?php
namespace Attogram;

$this->page_header('Import');

$default['s'] = '';
$default['t'] = '';
$default['d'] = '=';

if( $_POST ) {
  do_import( $this->sqlite_database );
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
    <label for="d">Deliminator</label>
    <br />
    <input type="text" class="form-control" name="d" value="<?php print $default['d']; ?>">
  </div>
  <div class="col-xs-4">
    <label for="t">Target Language Code</label>
    <br />
    <input type="text" class="form-control" name="t" value="<?php print $default['t']; ?>">
  </div>

  <div class="col-xs-12"><br /></div>

  <div class="col-xs-6">
    <label for="sn">Source Language Name</label>
    <br />
    <input type="text" class="form-control" name="sn" value="">
  </div>
  <div class="col-xs-6">
    <label for="tn">Target Language Name</label>
    <br />
    <input type="text" class="form-control" name="tn" value="">
  </div>

  <div class="col-xs-12"><br /></div>

  <div class="col-xs-12">
    <label for="w">Word pairs:</label>
    <textarea class="form-control" name="w" rows="8"></textarea>
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
  
  $ote = new ote($db);
  
  $w = $_POST['w']; // List of word pairs

  $d = $_POST['d']; // Deliminator
  $d = str_replace('\t', "\t", $d); // allow real tabs

  $s = trim($_POST['s']); // Source Language Code
  $sn = $ote->get_language_name_from_code($s, $default=@$_POST['sn']);

  $t = trim($_POST['t']); // Target Language Code
  $tn = $ote->get_language_name_from_code($t, $default=@$_POST['tn']);

  $lines = explode("\n", $w);
  print '<div class="container">';
  print 'Source Language: Code: <code>' . htmlentities($s) . '</code> Name: <code>' . htmlentities($sn) . '</code><br />';
  print 'Target Language: Code: <code>' . htmlentities($t) . '</code> Name: <code>' . htmlentities($tn) . '</code><br />';
  print 'Deliminator: <code>' . htmlentities($d) . '</code><br />';
  print 'Lines: <code>' . sizeof($lines) . '</code><hr /><small>';

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
    
    $si = $ote->get_id_from_word($sw);
    $ti = $ote->get_id_from_word($tw);
    
    $sql = 'INSERT INTO word2word (s_id, s_code, t_id, t_code) VALUES (:s_id, :s_code, :t_id, :t_code)';
    $bind = array( 's_id'=>$si, 's_code'=>$s, 't_id'=>$ti, 't_code'=>$t);
    $r = $db->queryb($sql, $bind);
    if( !$r ) {
      if( $db->db->errorCode() == '0000' ) {
        //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
        $error_count++; $dupe_count++; $skip_count++;
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
      
      if( $line_count % 100 == 0 ) {
        print ' ' . $line_count . ' ';
      } elseif( $line_count % 10 == 0 ) {
        print '.';
      }
       
    }

    @ob_flush(); flush();

  } // end foreach line
  
  print '</small><hr />';
  print '<code>' . $import_count . '</code> word pairs imported.<br />';
  print '<code>' . $error_count . '</code> errors.<br />';
  print '<code>' . $dupe_count . '</code> duplicate lines.<br />';
  print '<code>' . $skip_count . '</code> lines skipped.<br />';
  print '</div>';
  
} // end do_import
