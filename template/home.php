<?php
/**
 * Open Translation Engine v2
 * Home Template
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 *
 * @var OpenTranslationEngine $this
 */
use Attogram\OpenTranslationEngine\OpenTranslationEngine;

?>
<h1><?= $this->getData('headline'); ?></h1>
<h2><?= $this->getData('subhead'); ?></h2>

<h3><a href="languages/">🌐 <code><?= $this->getDataInt('languageCount'); ?></code> Languages</a></h3>
<h3><a href="dictionary/">📚 <code><?= $this->getDataInt('dictionaryCount'); ?></code> Dictionaries</a></h3>
<h3><a href="word/">🔤 <code><?= $this->getDataInt('wordCount'); ?></code> Words</a></h3>
<h3><a href="slush_pile/">🛃 <code><?= $this->getDataInt('slushPileCount'); ?></code> submissions</a></h3>
<h3><a href="search/">🔎 Search</a></h3>
<h3><a href="export/">📤 Export</a></h3>
<h3><a href="history/">🔭 History</a></h3>

<p>Admin:</p>
<h3><a href="user-admin/">👥 <code><?= $this->getDataInt('userCount'); ?></code> Users</a></h3>
<h3><a href="import/">📥 Import Translations</a></h3>
<h3><a href="languages-admin/">🌐 Languages Admin</a></h3>
<h3><a href="tags-admin/">⛓ Tags Admin</a></h3>
<h3><a href="events/">⌚ <code><?= $this->getDataInt('eventCount'); ?></code> events</a></h3>
<h3><a href="info/">🚀 Site Information</a></h3>
