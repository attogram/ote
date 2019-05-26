<h1>
    <?= Attogram\OpenTranslationEngine\OpenTranslationEngine::OTE_NAME ?>
    v<?= Attogram\OpenTranslationEngine\OpenTranslationEngine::OTE_VERSION ?>
</h1>
<h2>
    a collaborative translation dictionary
</h2>
<h3><a href="languages/">🌐 <code><?= $this->getLanguagesCount(); ?></code> Languages</a></h3>
<h3><a href="dictionary/">📚 <code><?= $this->getDictionaryCount(); ?></code> Dictionaries</a></h3>
<h3><a href="word/">🔤 <code><?= $this->getWordCount(); ?></code> Words</a></h3>
<h3><a href="slush_pile/">🛃 <code><?= $this->getCountSlushPile(); ?></code> submissions</a></h3>
<h3><a href="search/">🔎 Search</a></h3>
<h3><a href="export/">📤 Export</a></h3>
<h3><a href="history/">🔭 History</a></h3>
<h3><a href="readme/">💁 About</a></h3>

<p>Admin:</p>
<h3><a href="user-admin/">👥 <code><?php /* print $this->database->getTableCount('user'); */ ?></code> Users</a></h3>
<h3><a href="import/">📥 Import Translations</a></h3>
<h3><a href="languages-admin/">🌐 Languages Admin</a></h3>
<h3><a href="tags-admin/">⛓ Tags Admin</a></h3>

<p>Debug:</p>
<h3><a href="events/">⌚ <code><?php /* print $this->database->getTableCount('event'); */ ?></code> events</a></h3>
<h3><a href="info/">🚀 Site Information</a></h3>
<h3><a href="db-admin/">🔧 DB admin</a></h3>
<h3><a href="db-tables/">📜 DB tables</a></h3>
<h3><a href="find_3rd_level/">🔦 Find 3rd levels</a></h3>
<h3><a href="check.php">🔬 Install check</a></h3>
