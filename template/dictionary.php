<?php
/**
 * Open Translation Engine v2
 * List of Dictionaries Template
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 *
 * @var OpenTranslationEngine $this
 */
use Attogram\OpenTranslationEngine\OpenTranslationEngine;

?>
<h1>📚 <?= $this->getData('dictionaryCount'); ?> Dictionaries</h1>
<pre>
    sourceLanguage: <?= $this->getDataInt('sourceLanguage'); ?> &nbsp;
    targetLanguage: <?= $this->getDataInt('targetLanguage'); ?> &nbsp;
</pre>
<?php

foreach ($this->getDataArray('dictionaries') as $index => $dictionary) {
    print "<br>* index:$index dictionary:" . print_r($dictionary, true);
}
