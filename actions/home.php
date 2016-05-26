<?php
namespace Attogram;

$this->page_header('Open Translation Engine v' . OTE_VERSION);

?>
<div class="container">
<h1>Open Translation Engine (OTE)</h1>
<p>The OTE is a <strong>translation dictionary</strong> manager for the web.</p>
<p>The OTE source is <strong>open source</strong>, it is dual licensed under the 
   <strong>MIT License</strong> or the <strong>GNU General Public License</strong>, 
   at your choosing.</p>
<p>The OTE content is <strong>open content</strong>, words and translations are 
   licened under the <strong>Creative Commons Attribution-Share Alike license</strong>, or similar.</p>
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
