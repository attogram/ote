<?php
// Open Translation Engine - Search Page v0.3.2

namespace Attogram;

$ote = new OpenTranslationEngine($this);

$this->pageHeader('Search');

if (isset($_GET['s']) && $_GET['s']) { // Source Language
    $s_selected = urldecode($_GET['s']);
} else {
    $s_selected = '';
}

if (isset($_GET['t']) && $_GET['t']) { // Target Language
    $t_selected = urldecode($_GET['t']);
} else {
    $t_selected = '';
}

if (isset($_GET['q']) && $_GET['q']) { // The Query
    $q_default = $this->webDisplay(urldecode($_GET['q']));
} else {
    $q_default = '';
}

if (isset($_GET['f']) && $_GET['f']=='f') { // Fuzzy Search
    $f_default = ' checked';
} else {
    $f_default = '';
}

if (isset($_GET['c']) && $_GET['c']=='c') { // Case Sensative
    $c_default = ' checked';
} else {
    $c_default = '';
}

?>
<div class="container">
 <form action="." method="GET">
 <div class="row">
  <div class="form-group col-md-6">
   <label for="s">Source Language:</label>
   <?php print $ote->getLanguagesPulldown($name = 's', $s_selected); ?>
  </div>
  <div class="form-group col-md-6">
   <label for="t">Target Language:</label>
   <?php print $ote->getLanguagesPulldown($name = 't', $t_selected); ?>
  </div>
 </div>
 <div class="row">
  <div class="form-group col-md-12">
   <label for="q">Query:</label>
   <input type="text" class="form-control" name="q" value="<?php print $q_default; ?>">
  </div>
 </div>
 <div class="row">
  <div class="form-group col-md-12 text-right">
    <label class="checkbox-inline">
     <input name="f" type="checkbox" value="f"<?php print $f_default; ?>>💨 Fuzzy Search
    </label>
    &nbsp; &nbsp; &nbsp;
    <label class="checkbox-inline">
      <input name="c" type="checkbox" value="c"<?php print $c_default; ?>>🔠 Case Sensitive
    </label>
  </div>
</div>
 <div class="row">
  <div class="form-group col-md-12">
   <button type="submit" class="btn btn-primary btn-sm btn-block">
     <h4>
       <span class="glyphicon glyphicon-search" aria-hidden="true"></span> &nbsp; Search
     </h4>
    </button>
  </div>
 </div>
 </form>
</div>
<?php

if (isset($_GET['q']) && $_GET['q']) { // If Querying

    $search_word = trim(urldecode($_GET['q']));

    if (isset($_GET['s']) && $_GET['s']) { // Source Language
        $s = urldecode($_GET['s']);
    } else {
        $s = '';
    }

    if (isset($_GET['t']) && $_GET['t']) { // Target Language
        $t = urldecode($_GET['t']);
    } else {
        $t = '';
    }

    if (isset($_GET['f']) && $_GET['f']=='f') { // Fuzzy Search?
        $fuzzy_search = true;
    } else {
        $fuzzy_search = false;
    }

    if (isset($_GET['c']) && $_GET['c']=='c') { // Case Sensitive Search?
        $caseSensitive_search = false;
    } else {
        $caseSensitive_search = true;
    }

    print '<div class="container"><h1>Search: <kbd>' . $this->webDisplay($search_word) . '</kbd></h1>';

    $sourceLanguageId = $targetLanguageId = 0;
    if ($s && $s !=  '') {
        $sourceLanguageId = $ote->getLanguageIdFromCode($s);
    }
    if ($t && $t != '') {
        $targetLanguageId = $ote->getLanguageIdFromCode($t);
    }

    list($limit, $offset) = $this->database->getSetLimitAndOffset(
        100,  // $defaultLimit
        0,    // $defaultOffset
        1000, // $maxLimit
        10    // $minLimit
    );

    $result_count = $ote->getCountSearchDictionary(
        $search_word,
        $sourceLanguageId,
        $targetLanguageId,
        $fuzzy_search,
        $caseSensitive_search
    );

    $result = $ote->searchDictionary(
        $search_word,
        $sourceLanguageId,
        $targetLanguageId,
        $fuzzy_search,
        $caseSensitive_search,
        $limit,
        $offset
    );

    $prepend_query_string = 's=' . urlencode($s) . '&amp;t=' . urlencode($t) . '&amp;q=' . urlencode($search_word);
    if ($fuzzy_search) {
        $prepend_query_string .= '&amp;f=f';
    }
    if (!$caseSensitive_search) {
        $prepend_query_string .= '&amp;c=c';
    }

    print $this->database->pager(
        $result_count,
        $limit,
        $offset,
        $prepend_query_string
    );

    foreach ($result as $r) {
        print $ote->displayPair(
            $r['s_word'],
            $r['sc'],
            $r['t_word'],
            $r['tc'],
            $this->path,
            ' = ',
            true,
            true
        );
    }
    print '</div>';
}

print '<br /><br />';
$this->pageFooter();