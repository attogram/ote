<?php // Open Translation Engine - Export Page v0.3.2
/*
 OTE Export Page

 Requires config setup:
   $config['depth']['export'] = 3;

 URL formats:

  export/source_language_code/target_language_code/
    export all translations, from source language, into target language

  export/source_language_code/
    export all translations, from source language, into any language

  export/
    list all dictionaries

*/
namespace Attogram;

$ote = new OpenTranslationEngine($this);

$rel_url = $this->path . '/' . urlencode($this->uri[0]) . '/';

if (sizeof($this->uri) == 1) { // list all exportable dictionaries
      $this->pageHeader('Export Translations');
      print '<div class="container"><h1 class="squished">📤 Export Translations</h1>';
      $dlist = $ote->getDictionaryList();
      print '<p><code>' . sizeof($dlist) . '</code> exportable Dictionaries:</p><ul>';
      foreach ($dlist as $url => $name) {
          print '<li><a href="' . $url . '">' . $name . '</a></li>';
      }
      print '</ul></div>';
      $this->pageFooter();
      exit;
}
// Please select Source Langauge Code
if (!isset($this->uri[1]) || !$this->uri[1]) {
    $this->error404('Sourceless exportless emptiness');
}
// Please select Target Language Code
if (!isset($this->uri[2]) || !$this->uri[2]) {
    $this->error404('Targetless exportless emptiness');
}

$s_code = urldecode($this->uri[1]);
$t_code = urldecode($this->uri[2]);

// Error - Source and Target language code the same
if ($s_code == $t_code) {
    $this->error404('Self-Referential Export denied');
}

$langs = $ote->getLanguages();

// Source Language Code Not Found
if (!isset($langs[$s_code])) {
    $this->error404('Emptiness in source');
}
// Target Language Code Not Found
if (!isset($langs[$t_code])) {
    $this->error404('Emptiness in target');
}

$result = $ote->getDictionary($langs[$s_code]['id'], $langs[$t_code]['id']);

$sep = ' = ';
$cr = "\n";

header('Content-Type: text/plain; charset=utf-8');

print '# ' . $langs[$s_code]['name']  . ' to ' . $langs[$t_code]['name'] . $cr
    . "# ($s_code to $t_code)" . $cr
    . '#' . $cr
    . '# translations: ' . sizeof($result) . $cr
    . "# deliminator: $sep" . $cr
    . '#' . $cr
    . '# export time: ' . gmdate('Y-m-d H:i:s') . ' UTC' . $cr
    . '# export from: ' . $this->siteName . ': ' . $this->getSiteUrl() . '/' . $cr
    . '# export with: Open Translation Engine v' . OpenTranslationEngine::OTE_VERSION
    . ' / Attogram Framework v' . attogram::ATTOGRAM_VERSION . ' / PHP v' . phpversion() . $cr
    . '#' . $cr
    . '# This work is licensed under the Creative Commons Attribution-Share Alike 3.0 Unported License.' . $cr
    . '# To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/3.0/' . $cr
    . '# or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.' . $cr
    . '#' . $cr;

foreach ($result as $r) {
    print $r['s_word'] . $sep . $r['t_word'] . "\n";
}

exit;
