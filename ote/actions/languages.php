<?php // Open Translation Engine - Languages Page v0.0.9

namespace Attogram;

$this->page_header('Languages');

$ote = new ote( $this->db, $this->log );

$langs = $ote->get_languages('name');

?>
<div class="container">
 <h1>Language List</h1>
 <p><code><?php print sizeof($langs); ?></code> Languages:</p>
 <dl class="dl-horizontal"><?php
    foreach( $langs as $code => $lang ) {

      $qr = $this->db->query('SELECT count(distinct sw) AS count FROM word2word WHERE sl = :sl', array('sl'=>$lang['id']));
      $num_words = isset($qr[0]['count']) ? $qr[0]['count'] : '0';

      $qr = $this->db->query('SELECT count(sw) AS count FROM word2word WHERE sl = :sl', array('sl'=>$lang['id']));
      $num_translations = isset($qr[0]['count']) ? $qr[0]['count'] : '0';
      $translations_url = $this->path . '/dictionary/' . $code . '//';


      $num_dictionaries = $ote->get_dictionary_count( $code );

      $dr = $ote->get_dictionary_list( $this->path . '/dictionary/', $code );
      $dictionaries = '';
      foreach( $dr as $url => $name) {
        $dictionaries .= ' &nbsp; &nbsp; <a href="' . $url . '">'
        . '<span class="glyphicon glyphicon-book" aria-hidden="true"></span> ' . $name . '</a><br />';
      }

      print '<hr />';
      print '<dt><h3 class="squished"><strong><kbd>' . $lang['name'] . '</kbd></strong></h3></dt>';
      print '<dd>code: <code>' . $code . '</code></dd>';
      print '<dd>' . $num_words . ' words</dd>';
      print '<dd><a href="' . $translations_url . '">' . $num_translations . '</a> translations</dd>';
      print '<dd>' . $num_dictionaries . ' dictionaries:</dd>';
      print '<dd>' .$dictionaries . '</dd>';
    }
  ?></dl><hr />
 </div>
<?php
$this->page_footer();
