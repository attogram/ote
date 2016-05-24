<?php
namespace Attogram;

$this->page_header('Open Translation Engine v' . OTE_VERSION);

?>
<div class="container">
<h2>The Open Translation Engine</h2>
<p>Welcome to the Open Translation Engine (OTE) version <?php print OTE_VERSION; ?>.</p>
<p>The OTE is a multi-user <nobr><strong>translation dictionary</strong></nobr> manager for the web.</p>
<p>The OTE is open source and open content.</p>
<ul>
  <li><a href="dictionary/">Dictionary</a></li>
  <li><a href="export/">Export</a></li>
  <li><a href="import/">Import</a></li>
</ul>
</div>
<?php
$this->page_footer();
