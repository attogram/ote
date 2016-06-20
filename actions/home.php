<?php // Open Translation Engine - Home Page v0.0.9

namespace Attogram;

$this->page_header('Open Translation Engine v' . OTE_VERSION);

$ote = new ote( $this->db, $this->log );
?>
<div class="container">
<h1>Open Translation Engine</h1>
<p>an open source, open content translation dictionary manager for the web.</p>
<h2><a href="search/"    >🔎 Search</a></h2>
<h2><a href="languages/" >🌐 <code><?php print $ote->get_languages_count();  ?></code> Languages</a></h2>
<h2><a href="dictionary/">📚 <code><?php print $ote->get_dictionary_count(); ?></code> Dictionaries</a></h2>
<h2><a href="word/"      >🔤 <code><?php print $ote->get_word_count();       ?></code> Words</a></h2>
<h2><a href="export/"    >📤 Export</a></h2>
<h2><a href="history/"   >🔭 History</a></h2>
<h2><a href="about/"     >💁 About</a></h2>
</div>
<?php

$this->page_footer();
