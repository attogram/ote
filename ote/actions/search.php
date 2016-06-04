<?php
// Open Translation Engine - Search Page v0.0.8

namespace Attogram;

$ote = new ote($this->db, $this->log);

$this->page_header('Search');

if( isset($_GET['l']) && $_GET['l'] ) {
  $selected = urldecode( $_GET['l'] );
} else {
  $selected = '';
}

if( isset($_GET['q']) && $_GET['q'] ) {
  $q_default = htmlentities(urldecode($_GET['q']));
} else {
  $q_default = '';
}

?>
<div class="container">

 <form action="." method="GET">

 <div class="form-group">
  <label for="q">Query</label>
  <input type="text" class="form-control" name="q" value="<?php print $q_default; ?>">
 </div>

  <div class="form-group">
   <label for="l">Language</label>
   <?php print $ote->get_languages_pulldown($name='l', $selected); ?>
  </div>

  <div class="form-group">
   <button type="submit" class="btn btn-primary">Search</button>
  </div>

 </form>

</div>

<?php

print '<div class="container">';
if( isset($_GET['q']) && $_GET['q'] ) {
  $q = urldecode($_GET['q']);

  if( isset($_GET['l']) && $_GET['l'] ) {
    $sl = urldecode($_GET['l']);
  } else {
    $sl = '';
  }


  $result = $ote->search($q, $sl);
  print $result;
}
print '</div>';

$this->page_footer();
