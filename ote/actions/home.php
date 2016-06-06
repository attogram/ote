<?php // Open Translation Engine - Home Page v0.0.8

namespace Attogram;

$this->page_header('Open Translation Engine v' . OTE_VERSION);

?>
<div class="container">
<h1>Open Translation Engine (OTE)</h1>
<p>The OTE is a <strong>translation dictionary</strong> manager for the web.
<a href="about/">More about the OTE...</a></p>

<ul>
  <li><a href="search/">Search</a></li>
  <li><a href="languages/">Language list</a></li>
  <li><a href="dictionary/">Dictionary list</a></li>
  <li><a href="word/">Word list</a></li>
  <li><a href="export/">Export</a></li>
  <li><a href="import/">Import</a></li>
</ul>
</div>
<?php
$this->page_footer();
