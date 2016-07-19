<?php // Open Translation Engine - Home Page v0.3.1

namespace Attogram;

$ote = new OpenTranslationEngine($this);

$this->pageHeader('Open Translation Engine v' . OpenTranslationEngine::OTE_VERSION);

?>
<div class="container">
 <div class="row">
  <div class="col-xs-12 text-center">
   <h1 class="squished">Open Translation Engine</h1>
   <p>a collaborative <strong>translation dictionary manager</strong> for the open content web.</p>
  </div>
 </div>
 <div class="row">
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <h2><a href="languages/" >🌐 <code><?php print $ote->getLanguagesCount();  ?></code> Languages</a></h2>
   <h2><a href="dictionary/">📚 <code><?php print $ote->getDictionaryCount(); ?></code> Dictionaries</a></h2>
   <h2><a href="word/"      >🔤 <code><?php print $ote->getWordCount();       ?></code> Words</a></h2>
   <h2><a href="slush_pile/">🛃 <code><?php print $ote->getCountSlushPile(); ?></code> submissions</a></h2>
  </div>
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <h2><a href="search/"    >🔎 Search</a></h2>
   <h2><a href="export/"    >📤 Export</a></h2>
   <h2><a href="history/"   >🔭 History</a></h2>
   <h2><a href="readme/"    >💁 About</a></h2>
  </div>
 </div>
</div>
<?php

if (!$this->isAdmin()) {
  $this->pageFooter();
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
   <h3><a href="user-admin/"     >👥 <code><?php print $this->database->getTableCount('user'); ?></code> Users</a></h3>
   <h3><a href="import/"         >📥 Import Translations</a></h3>
   <h3><a href="languages-admin/">🌐 Languages Admin</a></h3>
   <h3><a href="tags-admin/"     >⛓ Tags Admin</a></h3>
  </div>
  <div class="col-xs-2"></div>
  <div class="col-xs-10 col-sm-5">
   <p><strong>Debug:</strong></p>
   <h4><a href="events/"        >⌚ <code><?php print $this->database->getTableCount('event'); ?></code> events</a></h4>
   <h4><a href="info/"          >🚀 Site Information</a></h4>
   <h4><a href="db-admin/"      >🔧 DB admin</a></h4>
   <h4><a href="db-tables/"     >📜 DB tables</a></h4>
   <h4><a href="find_3rd_level/">🔦 Find 3rd levels</a></h4>
   <h4><a href="check.php"      >🔬 Install check</a></h4>
  </div>
 </div>
</div>
<?php
$this->pageFooter();
