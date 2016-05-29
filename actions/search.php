<?php
// Open Translation Engine - Search Page v0.0.1

namespace Attogram;

$this->page_header('Search');
?>
<div class="container">
 <form action="." method="GET">
  <label for="q">Search</label>
  <input type="text" class="form-control" name="q" value="">
 </form>
 <hr />
<?php

if( isset($_GET['q']) && $_GET['q'] ) {
  $ote = new ote($this->sqlite_database);
  $result = $ote->search($_GET['q']);
  print $result;
}


print '</div>';

$this->page_footer();
