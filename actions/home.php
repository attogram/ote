<?php
namespace Attogram;

$this->page_header('Open Translation Engine v' . OTE_VERSION);

?>
<div class="container">
<h1>The Open Translation Engine</h1>
<p>Welcome to the Open Translation Engine (OTE) version <?php print OTE_VERSION; ?>.</p>
<p>The OTE is a multi-user <nobr><strong>translation dictionary</strong></nobr> manager for the web.</p>
<p>The OTE is open source and open content.</p>
<ul>
  <li><a href="dictionary/">Dictionary list</a></li>
  <li><a href="word/">Word list</a></li>
</ul>
<ul>
  <li><a href="export/">Export</a></li>
  <li><a href="import/">Import</a></li>
</ul>
</div>
<?php
$this->page_footer();
