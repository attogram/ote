<?php // Open Translation Engine - Home Page v0.1.0

namespace Attogram;

$ote = new ote( $this->db, $this->log );

$this->page_header('Open Translation Engine v' . ote::OTE_VERSION);

?>
<div class="container">
 <div class="row">
  <div class="col-xs-12 text-center">
   <h1 class="squished">Open Translation Engine</h1>
   <p>an open source <strong>translation dictionary manager</strong> for the open content web.</p>
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

if( !$this->is_admin() ) {
  $this->page_footer();
  exit;
}

// admin only menu
?>
<br /><hr />
<div class="container">
 <div class="row">
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <p><strong>Admin:</strong></p>
   <h3><a href="user-admin/"     >👥 <code><?php print $this->db->get_table_count('user'); ?></code> Users</a></h3>
   <h3><a href="import/"         >📥 Import Translations</a></h3>
   <h3><a href="languages-admin/">🌐 Languages Admin</a></h3>
   <h3><a href="tags-admin/"     >⛓ Tags Admin</a></h3>
  </div>
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <p><strong>Debug:</strong></p>
   <h4><a href="info/"          >🚀 Site Information</a></h4>
   <h4><a href="check.php"      >🔬 Install check</a></h4>
   <h4><a href="db-admin/"      >🔧 DB admin</a></h4>
   <h4><a href="db-tables/"     >📜 DB tables</a></h4>
   <h4><a href="find_3rd_level/">🔦 Find 3rd levels</a></h4>

  </div>
 </div>
</div>
<?php
$this->page_footer();
