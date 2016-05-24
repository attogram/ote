<?php
namespace Attogram;

$this->page_header('Open Translation Engine ' . OTE_VERSION);

?>
<div class="container">
<h2>The Open Translation Engine</h2>
<p>
 Welcome to the Open Translation Engine (OTE) v<?php print OTE_VERSION; ?>.
 The OTE is an open source, open content, multi-user
 <nobr><strong>translation dictionary</strong></nobr>
 manager for the web.
</p>

<ul>
  <li><a href="dictionary/">Dictionary</a></li>
  <li><a href="export/">Export</a></li>
  <li><a href="import/">Import</a></li>
</ul>

</div>
<?php
$this->page_footer();
