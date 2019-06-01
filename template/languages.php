<?php
/**
 * Open Translation Engine v2
 * List of Languages Template
 *
 * @license MIT
 * @see https://github.com/attogram/ote
 *
 * @var OpenTranslationEngine $this
 */
use Attogram\OpenTranslationEngine\OpenTranslationEngine;

?>
<h1>🌐 <code><?= $this->getDataInt('languageCount'); ?></code> Languages</h1>
<?php

foreach ($this->getDataArray('languages') as $language) {
    ?>
<p style="margin-left:20px;">
    Language: <b><?= $language['name']; ?></b><br>
    Code: <b><?= $language['code']; ?></b><br>
    # Dictionaries: <b><?= $language['dictionaryCount']; ?></b><br>
    # Words: <b><?= $language['wordCount']; ?></b><br>
    # Translations: <b><?= $language['translationCount']; ?></b><br>
</p>
    <?php
}
