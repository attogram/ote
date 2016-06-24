<?php // Open Translation Engine - Home Page v0.0.11

namespace Attogram;

$ote = new ote( $this->db, $this->log );

$this->page_header('Open Translation Engine v' . ote::OTE_VERSION);

?>
<div class="container">
<h1>Open Translation Engine</h1>
<p>an open source, open content <strong>translation dictionary manager</strong> for the web.</p>
<h2><a href="search/"    >🔎 Search</a></h2>
<h2><a href="languages/" >🌐 <code><?php print $ote->get_languages_count();  ?></code> Languages</a></h2>
<h2><a href="dictionary/">📚 <code><?php print $ote->get_dictionary_count(); ?></code> Dictionaries</a></h2>
<h2><a href="word/"      >🔤 <code><?php print $ote->get_word_count();       ?></code> Words</a></h2>
<h2><a href="slush_pile/">🛃 <code><?php print $ote->get_count_slush_pile(); ?></code> submissions</a></h2>
<h2><a href="export/"    >📤 Export</a></h2>
<h2><a href="history/"   >🔭 History</a></h2>
<h2><a href="about/"     >💁 About</a></h2>
</div>
<?php

$this->page_footer();
