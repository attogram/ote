<?php // Open Translation Engine - Languages Page v0.3.0

namespace Attogram;

$ote = new OpenTranslationEngine( $this );

$langs = $ote->getLanguages( $sortby = 'name' );

$this->pageHeader('🌐 ' . sizeof($langs) . ' Languages');

?>
<div class="container">
 <h1 class="squished">🌐 <code><?php print sizeof($langs); ?></code> Languages</h1>

 <div class="row" style="padding:8px; font-weight:bold; color:#999;">
   <div class="col-sm-3">Language:</div>
   <div class="col-sm-2">Code:</div>
   <div class="col-sm-3">Dictionaries:</div>
   <div class="col-sm-2">Words:</div>
   <div class="col-sm-2">Translations:</div>
 </div>
<?php
    foreach( $langs as $code => $lang ) {

      $qr = $this->database->query('SELECT count(distinct sw) AS count FROM word2word WHERE sl = :sl', array('sl'=>$lang['id']));
      $num_words = isset($qr[0]['count']) ? $qr[0]['count'] : '0';

      $qr = $this->database->query('SELECT count(sw) AS count FROM word2word WHERE sl = :sl', array('sl'=>$lang['id']));
      $num_translations = isset($qr[0]['count']) ? $qr[0]['count'] : '0';
      $translations_url = $this->path . '/dictionary/' . $code . '//';
      $word_list_url = $this->path .'/word/' . $code . '/';
      $num_dictionaries = $ote->getDictionaryCount( $code );

      $dr = $ote->getDictionaryList( $code );
      $dictionaries = '';
      foreach( $dr as $url => $name) {
        $dictionaries .= '<a href="' . $this->path . '/dictionary/' . $url . '">📖 ' . $name . '</a><br />';
      }

      print '
      <div class="row" style="border:1px solid #ccc; padding:8px;">
        <div class="col-sm-3"><h2><kbd>' . $lang['name'] . '</kbd></h2></div>
        <div class="col-sm-2"><h3><code>' . $code . '</code></h3></div>
        <div class="col-sm-3">' . $num_dictionaries . ' dictionaries:<br />' .$dictionaries . '</div>
        <div class="col-sm-2"><a href="' . $word_list_url . '">' . $num_words . '</a> words</div>
        <div class="col-sm-2"><a href="' . $translations_url . '">' . $num_translations . '</a> translations</div>
      </div>';

    }
  ?>
 </div>
<?php
$this->pageFooter();
