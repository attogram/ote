<?php
// Attogram Framework - 404 Page v0.0.1

header("HTTP/1.0 404 Not Found");

$this->page_header('404 Page Not Found');
?>
<div class="container">
  <h1>Error 404 Page Not Found</h1>
</div>
<?php
$this->page_footer();
