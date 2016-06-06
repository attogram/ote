<?php // Open Translation Engine - Search Page v0.0.9

namespace Attogram;

$ote = new ote($this->db, $this->log);

$this->page_header('Search');

if( isset($_GET['s']) && $_GET['s'] ) { // Source Language
  $s_selected = urldecode( $_GET['s'] );
} else {
  $s_selected = '';
}

if( isset($_GET['t']) && $_GET['t'] ) { // Target Language
  $t_selected = urldecode( $_GET['t'] );
} else {
  $t_selected = '';
}

if( isset($_GET['q']) && $_GET['q'] ) { // The Query
  $q_default = htmlentities(urldecode($_GET['q']));
} else {
  $q_default = '';
}

if( isset($_GET['f']) && $_GET['f']=='f' ) { // Fuzzy Search
  $f_default = ' checked';
} else {
  $f_default = '';
}

if( isset($_GET['c']) && $_GET['c']=='c' ) { // Case Sensative
  $c_default = ' checked';
} else {
  $c_default = '';
}

?>
<div class="container">

 <form action="." method="GET">

  <div class="form-group col-md-6">
   <label for="s">Source Language:</label>
   <?php print $ote->get_languages_pulldown($name='s', $s_selected); ?>
  </div>

  <div class="form-group col-md-6">
   <label for="t">Target Language:</label>
   <?php print $ote->get_languages_pulldown($name='t', $t_selected); ?>
  </div>

  <div class="form-group col-md-12">
   <label for="q">Query:</label>
   <input type="text" class="form-control" name="q" value="<?php print $q_default; ?>">
  </div>

  <div class="form-group col-md-12">
   <button type="submit" class="btn btn-primary btn-block">
     <h4>
       <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
       &nbsp; Search
     </h4>
    </button>
  </div>

  <div class="form-group col-md-2">
    Options:
  </div>
  <div class="form-group col-md-10">
    <label class="checkbox-inline">
     <input name="f" type="checkbox" value="f"<?php print $f_default; ?>> Fuzzy Search
    </label>
    &nbsp; &nbsp;
    <label class="checkbox-inline">
      <input name="c" type="checkbox" value="c"<?php print $c_default; ?>> Case Sensitive
    </lable>
  </div>

 </form>

</div>

<?php


if( isset($_GET['q']) && $_GET['q'] ) {
  $q = trim(urldecode($_GET['q']));

  if( isset($_GET['s']) && $_GET['s'] ) {
    $s = urldecode($_GET['s']);
  } else {
    $s = '';
  }

  if( isset($_GET['t']) && $_GET['t'] ) {
    $t = urldecode($_GET['t']);
  } else {
    $t = '';
  }

  print '<div class="container"><h1>Search: <kbd>' . htmlentities($q) . '</kbd></h1>';
  $result = $ote->search($q, $s, $t);
  print '<p><code>' . sizeof($result) . '</code> translations</p><hr />';
  $subhead = $prev_subhead = '';
  foreach( $result as $r ) {
    $durl = $this->path . '/dictionary/' . $r['sc'] . '/' . $r['tc'] . '/';
    $subhead = '<a href="' . $durl . '"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> '
    . $r['sn'] . ' to ' . $r['tn'] . '</a><br />';
    if( $subhead != $prev_subhead ) {
        print $subhead;
    }
    $prev_subhead = $subhead;
    print $ote->display_pair( $r['s_word'], $r['sc'], $r['t_word'], $r['tc'], $this->path, ' = ', TRUE, TRUE );
    print '<br />';
  }
  print '</div>';
}


$this->page_footer();
