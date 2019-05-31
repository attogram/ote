<?php
/**
 * Open Translation Engine
 * Home Template
 *
 * @license MIT
 * @see https://github.com/attogram/ote
 */
?>
<h1><?= $this->getData('headline'); ?></h1>
<h2><?= $this->getData('subhead'); ?></h2>

<h3><a href="languages/">🌐 <code><?= $this->getData('languageCount'); ?></code> Languages</a></h3>
<h3><a href="dictionary/">📚 <code><?= $this->getData('dictionaryCount'); ?></code> Dictionaries</a></h3>
<h3><a href="word/">🔤 <code><?= $this->getData('wordCount'); ?></code> Words</a></h3>
<h3><a href="slush_pile/">🛃 <code><?= $this->getData('slushPileCount'); ?></code> submissions</a></h3>
<h3><a href="search/">🔎 Search</a></h3>
<h3><a href="export/">📤 Export</a></h3>
<h3><a href="history/">🔭 History</a></h3>
<h3><a href="readme/">💁 About</a></h3>

<p>Admin:</p>
<h3><a href="user-admin/">👥 <code><?= $this->getData('userCount'); ?></code> Users</a></h3>
<h3><a href="import/">📥 Import Translations</a></h3>
<h3><a href="languages-admin/">🌐 Languages Admin</a></h3>
<h3><a href="tags-admin/">⛓ Tags Admin</a></h3>

<p>Debug:</p>
<h3><a href="events/">⌚ <code><?= $this->getData('eventCount'); ?></code> events</a></h3>
<h3><a href="info/">🚀 Site Information</a></h3>
<h3><a href="db-admin/">🔧 DB admin</a></h3>
<h3><a href="db-tables/">📜 DB tables</a></h3>
<h3><a href="find_3rd_level/">🔦 Find 3rd levels</a></h3>
<h3><a href="check.php">🔬 Install check</a></h3>
