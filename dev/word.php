<?php
// Open Translation Engine - Word Page v0.4.3
/*
 OTE Word Page

 Requires config setup:
   $config['depth']['word'] = 4;

 URL formats:

  word/source_language_code/target_language_code/word
    translations for this word, from source language, into target language

  word/source_language_code//word
    translations for this word, from source language, into any language

  word///word
    translations for this word, from any language, into any language

  word/source_language_code/target_language_code/
      all words in source language with translations in target language

  word//target_language_code/
      all words with translations in target language

  word/source_language_code/
    all words in source language

  word/
    all words

*/

namespace Attogram;

$ote = new OpenTranslationEngine($this);

$langs = $ote->getLanguages();

// Check Languages exist
$s_code = urldecode($this->uri[1]);
if ($s_code && !isset($langs[$s_code])) {
    $this->error404('Source Language not found yet');
}
$t_code = urldecode($this->uri[2]);
if ($t_code && !isset($langs[$t_code])) {
    $this->error404('Target Language not found yet');
}

list($limit, $offset) = $this->database->getSetLimitAndOffset(
    1000, // $defaultLimit
    0,    // $defaultOffset
    5000, // $maxLimit
    100   // $minLimit
);

switch (sizeof($this->uri)) { // Show Word lists
    case 1:
        showAllWords($ote, $limit, $offset);
        break;
    case 2:
        showAllWords($ote, $limit, $offset, $this->uri[1]);
        break;
    case 3:
        showAllWords($ote, $limit, $offset, $this->uri[1], $this->uri[2]);
        break;
    case 4:
        if (!$this->uri[3]) {
            $this->error404('The Word is the Bird.  Missing Bird.');
        }
        break;
}

if (sizeof($this->uri) > 4) { // Check URI is OK
    $this->error404('No Swimming in the Deep End of the word');
}

$word = urldecode($this->uri[3]);

$this->log->debug("word.php: s_code=$s_code t_code=$t_code word=" . $this->webDisplay($word));

$r = $ote->searchDictionary(
    $word,
    $ote->getLanguageIdFromCode($s_code),
    $ote->getLanguageIdFromCode($t_code)
);

if (!$r) {
    $this->log->error("word.php: No Translations Found");
    $this->error404('Nothing found but wordly emptiness');
}

$this->pageHeader('Word: ' . $this->webDisplay($word));
print '<div class="container">';

if ($_POST) {
    $sourceWord = isset($this->uri[3]) ? urldecode($this->uri[3]) : null;
    $targetWord = isset($_POST['tw']) ? urldecode($_POST['tw']) : null;
    $sourceLanguageCode = isset($_POST['sl']) ? urldecode($_POST['sl']) : null;
    $targetLanguageCode = isset($_POST['tl']) ? urldecode($_POST['tl']) : null;
    $type = isset($_POST['type']) ? urldecode($_POST['type']) : null;
    if (!$sourceWord || !$targetWord || !$sourceLanguageCode || !$targetLanguageCode) {
        print '<div class="alert alert-danger">'
            . '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
              . '<strong>Error adding translation</strong>: missing required word and/or languages</div>';
    } else {
        $items = array(
            'type' => $type,
            'source_word' => $sourceWord,
            'target_word' => $targetWord,
            'source_language_code' => $sourceLanguageCode,
            'target_language_code' => $targetLanguageCode
        );
        if ($ote->addToSlushPile($items)) {
            print '<div class="alert alert-success">'
                . '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                . '<strong>Thanks for your submission.</strong>'
                . ' New translation added to the <a target="_slush" href="../../../slush_pile/">Slush Pile</a></div>';
        } else {
            print '<div class="alert alert-danger">'
                . '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                . '<strong>Error adding translation</strong></div>';
        }
    }
} // end if _POST

print '<div style="font-size:48pt;">' . $this->webDisplay($word) . '</div>';

if ($s_code && $t_code) {
    $header = '<strong>' . $langs[$s_code]['name'] . '</strong> (<code>' . $s_code . '</code>)'
        . ' to <strong>' . $langs[$t_code]['name'] . '</strong> (<code>' . $t_code . '</code>)';
    $put_s = false;
    $put_t = false;
} elseif ($s_code && !$t_code) {
    $header = '<strong>' . $langs[$s_code]['name'] . '</strong> (<code>' . $s_code . '</code>)'
        . ' to <strong>All languages</strong> (<code>*</code>)';
    $put_s = true;
    $put_t = true;
} else {
    $header = '<strong>All languages</strong> (<code>*</code>)';
    $put_s = true;
    $put_t = false;
}
if (sizeof($r) == 1) {
    $post_s = '';
} else {
    $post_s = 's';
}
print '<p class="text-muted"><strong><code>' . sizeof($r) . '</code></strong> translation'
    . $post_s . ': ' . $header . '</p>';

foreach ($r as $w) {
    print $ote->displayPair(
        $w['s_word'], // The Source Word
        $w['sc'],     // The Source Language Code
        $w['t_word'], // The Target Word
        $w['tc'],     // The Target Language Code
        $this->path,  // URL path, defaults to ''
        ' = ',        //  The Deliminator, defaults to ' = '
        $put_s,       // Put Language Source Code in word URLS, defaults to true
        $put_t        // Put Language Target Code in word URLs, defaults to false
    );
}

print '
<div class="row" style="padding:2px;" name="addi" id="addi">
  <div class="col-xs-5">&nbsp;</div>
  <div class="col-xs-7 text-muted"><br /><a
    onclick="$(\'#add\').show();$(\'#addi\').hide();"
    href="javascript:void(0);"> + add translation</a></div>
</div>
<form name="add" id="add" method="POST" style="display:none;">
<input type="hidden" name="type" value="add">
  <div class="row" style="border:1px solid #eeeeee; padding:2px;">
    <div class="col-xs-4 text-left" style="font-size:18pt;">' . $this->webDisplay($word) . '</div>
    <div class="col-xs-1 text-center" style="font-size:18pt;"> = </div>
    <div class="col-xs-3 text-left"><input type="text" name="tw" /></div>
    <div class="col-xs-4 text-left" style="font-size:9pt;">'
    . $ote->getLanguagesPulldown('sl', $s_code, '')
    . ' = '
    . $ote->getLanguagesPulldown('tl', $t_code, '')
    . '</div>
  </div>
</form>
';

print '</div>'; // end main container div

$this->pageFooter();


/**
 * @param object $ote
 * @param int $limit
 * @param int $offset
 * @param int $scode
 * @param int $tcode
 */
function showAllWords($ote, $limit, $offset, $scode = 0, $tcode = 0)
{
    if (!$scode && !$tcode) {
        $allCount = $ote->getWordCount();
        $all = $ote->getAllWords($limit, $offset);
        $title = 'All Words';
    } elseif ($scode && !$tcode) {
        $allCount = $ote->getWordCount($ote->getLanguageIdFromCode($scode));
        $all = $ote->getAllWords(
            $limit,
            $offset,
            $ote->getLanguageIdFromCode($scode)
        );
        $title = $ote->getLanguageNameFromCode($scode) . ' Words';
    } elseif (!$scode && $tcode) {
        $allCount = $ote->getWordCount('', $ote->getLanguageIdFromCode($tcode));
        $all = $ote->getAllWords(
            $limit,
            $offset,
            '',
            $ote->getLanguageIdFromCode($tcode)
        );
        $title =  'Words with translations into ' . $ote->getLanguageNameFromCode($tcode);
    } elseif ($scode && $tcode) {
        $allCount = $ote->getWordCount(
            $ote->getLanguageIdFromCode($scode),
            $ote->getLanguageIdFromCode($tcode)
        );
        $all = $ote->getAllWords(
            $limit,
            $offset,
            $ote->getLanguageIdFromCode($scode),
            $ote->getLanguageIdFromCode($tcode)
        );
        $title = $ote->getLanguageNameFromCode($scode)
            . ' Words with translations into '
            . $ote->getLanguageNameFromCode($tcode);
    }
    $ote->attogram->pageHeader('🔤 ' . $title);
    print '<div class="container"><h1 class="squished">🔤 ' . $title . '</h1>';
    print $ote->attogram->database->pager($allCount, $limit, $offset);
    print '<style>a { color:inherit; }</style><h3>';
    foreach ($all as $w) {
        print '<a href="' . $ote->attogram->path . '/' . $ote->attogram->uri[0] . '///'
            . urlencode($w['word']) . '">' . $ote->attogram->webDisplay($w['word']) . '</a>, ';
    }
    print '</h3>';
    print $ote->attogram->database->pager($allCount, $limit, $offset);
    print '</div>';
    $ote->attogram->pageFooter();
    $ote->attogram->shutdown();
} // end function showAllWords()