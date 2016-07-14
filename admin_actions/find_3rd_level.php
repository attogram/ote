<?php // Open Translation Engine - Find 3rd Level v0.1.5

// IN DEV

namespace Attogram;

$ote = new ote( $this );

if( isset($_GET['j']) && $_GET['j'] ) {
  $join_language_id = (int)$_GET['j'];
} else {
  $join_language_id = 1;
}

if( isset($_GET['r']) && $_GET['r'] ) {
  $do_run = true;
} else {
  $do_run = false;
}


//     FIRST_LANG.word to JOIN_1_LANG.word
//   + JOIN_2_LANG.word  to SECOND_LANG.word
//  = FIRST_LANG.word to SECOND_LANG.word
$sql = '
SELECT first_word.word  AS FIRST_WORD,
       second_word.word AS SECOND_WORD,
       first_w2w.sw     AS FIRST_WORD_ID,
       join_word.word   AS JOIN_WORD,
       first_w2w.tw     AS JOIN_WORD_ID,
       second_w2w.tw    AS SECOND_WORD_ID,
       first_w2w.sl     AS FIRST_LANG_ID,
       second_w2w.tl    AS SECOND_LANG_ID

FROM word2word AS first_w2w, word AS first_word,
     word2word AS second_w2w, word AS second_word,
     word AS join_word

WHERE first_w2w.tw = second_w2w.sw  -- JOIN_LANG.word

AND   first_word.id = first_w2w.sw
AND   join_word.id = first_w2w.tw
AND   second_word.id = second_w2w.tw

AND   second_w2w.sl = first_w2w.tl  -- JOIN_2_LANG
AND   first_w2w.sw != second_w2w.tw -- no self ref
AND   first_w2w.sl != second_w2w.tl -- no self ref
AND   first_w2w.tl = :j              -- JOIN_1_LANG
';

$bind = array( 'j', $join_language_id );
$r = @$this->database->query( $sql, $bind );
// Warning: PDOStatement::bindParam(): SQLSTATE[HY093]: Invalid parameter number: Columns/Parameters are 1-based in .\public\index.php on line 86


//print '<pre>' . print_r($r,1) . '</pre>';

// todo: de dupe reverse entries

$langs = $ote->getLanguages();

$join_lang_code = $ote->getLanguageCodeFromId($join_language_id);

$this->pageHeader('3rd level translations');

print '<div class="container">'
. '<p>Join Language: '
. '<code><a href="?j=' . $join_language_id . '">' . $join_lang_code . '</a></code>'
. '<p><code>' . sizeof($r) . '</code> possible 3rd level translations:</p>';

$cleaned_r = array();
foreach( $r as $p ) {
  $test = $ote->get_word2word(
    $p['FIRST_WORD_ID'], $p['FIRST_LANG_ID'],
    $p['SECOND_WORD_ID'], $p['SECOND_LANG_ID'] );
  if( $test ) {
    continue; // word2word entry already exists
  }
  $cleaned_r[] = $p;
}

if( $do_run ) {
  print '<p>Doing Import Run</p>';
} else {
  print '<p>Preview - <a href="?j=' . $join_language_id . '&r=run">Do Run</a></p>';
}

print '<p><code>' . sizeof($cleaned_r) . '</code> new 3rd level translations:</p><hr />';

foreach( $cleaned_r as $p ) {
  $first_lang_code = $ote->getLanguageCodeFromId( $p['FIRST_LANG_ID'] );
  $second_lang_code = $ote->getLanguageCodeFromId( $p['SECOND_LANG_ID'] );
  print '<p>'
  . '<code>' . $first_lang_code . '</code> '
  . '<a href="' . $this->path . '/word/' . $first_lang_code . '//' . urlencode($p['FIRST_WORD']) . '">'
  . $p['FIRST_WORD'] . '</a>'
  . ' = '
  . ' <code>' . $join_lang_code . '</code>'
  . '<a href="' . $this->path . '/word/' . $join_lang_code . '//' . urlencode($p['JOIN_WORD']) . '">'
  . $p['JOIN_WORD'] . '</a>'
  . ' = '
  . ' <code>' . $second_lang_code . '</code>'
  . '<a href="' . $this->path . '/word/' . $second_lang_code . '//' . urlencode($p['SECOND_WORD']) . '">'
  . $p['SECOND_WORD'] . '</a>'
  ;

  if( $do_run ) {
    $in = $ote->insertWord2word(
      $p['FIRST_WORD_ID'], $p['FIRST_LANG_ID'],
      $p['SECOND_WORD_ID'], $p['SECOND_LANG_ID'] );
    if( $in ) {
      print ' -- INSERTED.';
    } else {
      print ' -- ERROR.';
    }
    $in = $ote->insertWord2word(
      $p['SECOND_WORD_ID'], $p['SECOND_LANG_ID'],
      $p['FIRST_WORD_ID'], $p['FIRST_LANG_ID'] );
    if( $in ) {
      print ' INSERTED REVERSE.';
    } else {
      print ' ERROR REVERSE.';
    }
  }

  print '</p>';

}

print '</div>';
$this->pageFooter();
