<?php // Open Translation Engine - Search Page v0.0.9

namespace Attogram;

$ote = new ote($this->db, $this->log);

$this->page_header('Search');

if( isset($_GET['s']) && $_GET['s'] ) {
  $s_selected = urldecode( $_GET['s'] );
} else {
  $s_selected = '';
}

if( isset($_GET['t']) && $_GET['t'] ) {
  $t_selected = urldecode( $_GET['t'] );
} else {
  $t_selected = '';
}

if( isset($_GET['q']) && $_GET['q'] ) {
  $q_default = htmlentities(urldecode($_GET['q']));
} else {
  $q_default = '';
}

?>
<div class="container">

 <form action="." method="GET">

  <div class="form-group col-xs-6">
   <label for="s">Source Language:</label>
   <?php print $ote->get_languages_pulldown($name='s', $s_selected); ?>
  </div>

  <div class="form-group col-xs-6">
   <label for="t">Target Language:</label>
   <?php print $ote->get_languages_pulldown($name='t', $t_selected); ?>
  </div>

  <div class="form-group col-xs-12">
   <label for="q">Query:</label>
   <input type="text" class="form-control" name="q" value="<?php print $q_default; ?>">
  </div>

  <div class="form-group col-xs-12">
   <button type="submit" class="btn btn-primary btn-block">
     <h4>
       <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
       &nbsp; Search
     </h4>
    </button>
  </div>

 </form>

</div>

<?php

print '<div class="container">';
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

  $result = $ote->search($q, $s, $t);
  //print '<pre>' . print_r($result,1) . '<pre>';
  reset($result);
  foreach( $result as $r ) {
    print $ote->display_pair( $r['s_word'], $r['sc'], $r['t_word'], $r['tc'], $this->path, ' = ', TRUE, TRUE );
    print '<br />';
  }
}
print '</div>';

$this->page_footer();
