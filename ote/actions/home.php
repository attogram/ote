<?php // Open Translation Engine - Home Page v0.0.9

namespace Attogram;

$this->page_header('Open Translation Engine v' . OTE_VERSION);

$ote = new ote( $this->db, $this->log );
?>
<div class="container">
<h1>Open Translation Engine <small>v<?php print OTE_VERSION; ?></small></h1>
<h2><p><a href="search/">🔎 &nbsp; Search</a></p></h2>
<h2><p><a href="languages/">🌐 &nbsp; <code><?php print $ote->get_languages_count(); ?></code> Languages</a></p></h2>
<h2><p><a href="dictionary/">📚 &nbsp; <code><?php print $ote->get_dictionary_count(); ?></code> Dictionaries</a></p></h2>
<h2><p><a href="word/">🔤 &nbsp; <code><?php print $ote->get_word_count(); ?></code> Words</a></p></h2>
<hr />
<h3><p><a href="export/">📤 &nbsp; Export</a></p></h3>
<h3><p><a href="history/">🔭 &nbsp; History</a></p></h3>
<hr />
<h4><p>The Open Translation Engine (OTE) is an open source <strong>translation dictionary</strong> manager for the web.</p></h4>
<h4><p><a href="about/">💁 More about the OTE</a></p></h4>
<h4><p><a href="https://github.com/attogram/ote"><svg aria-hidden="true" height="20" version="1.1" viewBox="0 0 16 16" width="28"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59 0.4 0.07 0.55-0.17 0.55-0.38 0-0.19-0.01-0.82-0.01-1.49-2.01 0.37-2.53-0.49-2.69-0.94-0.09-0.23-0.48-0.94-0.82-1.13-0.28-0.15-0.68-0.52-0.01-0.53 0.63-0.01 1.08 0.58 1.23 0.82 0.72 1.21 1.87 0.87 2.33 0.66 0.07-0.52 0.28-0.87 0.51-1.07-1.78-0.2-3.64-0.89-3.64-3.95 0-0.87 0.31-1.59 0.82-2.15-0.08-0.2-0.36-1.02 0.08-2.12 0 0 0.67-0.21 2.2 0.82 0.64-0.18 1.32-0.27 2-0.27 0.68 0 1.36 0.09 2 0.27 1.53-1.04 2.2-0.82 2.2-0.82 0.44 1.1 0.16 1.92 0.08 2.12 0.51 0.56 0.82 1.27 0.82 2.15 0 3.07-1.87 3.75-3.65 3.95 0.29 0.25 0.54 0.73 0.54 1.48 0 1.07-0.01 1.93-0.01 2.2 0 0.21 0.15 0.46 0.55 0.38C13.71 14.53 16 11.53 16 8 16 3.58 12.42 0 8 0z"></path></svg>
   OTE project at GitHub</a></p></h4>
<hr />
</div>
<?php
$this->page_footer();
