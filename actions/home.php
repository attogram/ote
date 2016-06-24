<?php // Open Translation Engine - Home Page v0.0.11

namespace Attogram;

$ote = new ote( $this->db, $this->log );

$this->page_header('Open Translation Engine v' . ote::OTE_VERSION);

?>
<div class="container">
 <div class="row">
  <div class="col-xs-12 text-center">
   <h1 class="squished">Open Translation Engine</h1>
   <p>an open source, open content <strong>translation dictionary manager</strong> for the web.</p>
  </div>
 </div>
 <div class="row">
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <h2><a href="languages/" >🌐 <code><?php print $ote->get_languages_count();  ?></code> Languages</a></h2>
   <h2><a href="dictionary/">📚 <code><?php print $ote->get_dictionary_count(); ?></code> Dictionaries</a></h2>
   <h2><a href="word/"      >🔤 <code><?php print $ote->get_word_count();       ?></code> Words</a></h2>
   <h2><a href="slush_pile/">🛃 <code><?php print $ote->get_count_slush_pile(); ?></code> submissions</a></h2>
  </div>
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <h2><a href="search/"    >🔎 Search</a></h2>
   <h2><a href="export/"    >📤 Export</a></h2>
   <h2><a href="history/"   >🔭 History</a></h2>
   <h2><a href="about/"     >💁 About</a></h2>
  </div>
 </div>
</div>
<?php

$this->page_footer();
