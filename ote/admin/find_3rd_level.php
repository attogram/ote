<?php // Open Translation Engine - Find 3rd Level v0.0.1

namespace Attogram;

$ote = new ote($this->db, $this->log);

if( isset($_GET['j']) && $_GET['j'] ) {
  $join_language_id = (int)$_GET['j'];
} else {
  $join_language_id = 1;
}
//     FIRST_LANG.word to JOIN_1_LANG.word
//   + JOIN_2_LANG.word  to SECOND_LANG.word
//  = FIRST_LANG.word to SECOND_LANG.word
$sql = '
SELECT first_word.word AS FIRST_WORD,
       second_word.word AS SECOND_WORD,
       first_w2w.sw AS FIRST_WORD_ID,
       second_w2w.tw AS SECOND_WORD_ID,
       first_w2w.sl AS FIRST_LANG_ID,
       second_w2w.tl AS SECOND_LANG_ID

FROM word2word AS first_w2w, word AS first_word,
     word2word AS second_w2w, word AS second_word

WHERE first_w2w.tw = second_w2w.sw  -- JOIN_LANG.word

AND   first_word.id = first_w2w.sw
AND   second_word.id = second_w2w.tw

AND   second_w2w.sl = first_w2w.tl  -- JOIN_2_LANG
AND   first_w2w.sw != second_w2w.tw -- no self ref
AND   first_w2w.sl != second_w2w.tl -- no self ref
AND   first_w2w.tl = :j              -- JOIN_1_LANG


';



$bind = array(':j',$join_language_id );
$r = @$ote->db->query($sql,$bind);
// Warning: PDOStatement::bindParam(): SQLSTATE[HY093]: Invalid parameter number: Columns/Parameters are 1-based in .\attogram\public\index.php on line 86


//print '<pre>' . print_r($r,1) . '</pre>';

// todo: de dupe reverse entries

$langs = $ote->get_languages();

$this->page_header('3rd test');
print '<div class="container">';
print '<p>Join Language: <code><a href="?j=' . $join_language_id . '">' 
. $ote->get_language_code_from_id($join_language_id) . '</a></code>';
print '<p><code>' . sizeof($r) . '</code> possible 3rd level translations:</p>';

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


print '<p><code>' . sizeof($cleaned_r) . '</code> new 3rd level translations:</p><hr />';

foreach( $cleaned_r as $p ) {

  print '<p>'
  . '<code>' . $ote->get_language_code_from_id( $p['FIRST_LANG_ID'] ) . '</code> '
  . '<a href="' . $this->path . '/word///' . urlencode($p['FIRST_WORD']) . '">'
  . $p['FIRST_WORD'] . '</a>'
  . ' = '
  . ' <code>' . $ote->get_language_code_from_id( $p['SECOND_LANG_ID'] ) . '</code>'
  . '<a href="' . $this->path . '/word///' . urlencode($p['SECOND_WORD']) . '">'
  . $p['SECOND_WORD'] . '</a>'
  ;

  $in = $ote->insert_word2word(
    $p['FIRST_WORD_ID'], $p['FIRST_LANG_ID'],
    $p['SECOND_WORD_ID'], $p['SECOND_LANG_ID'] );
  if( $in ) {
    print ' -- INSERTED.';
  } else {
    print ' -- ERROR.';
  }
  $in = $ote->insert_word2word(
    $p['SECOND_WORD_ID'], $p['SECOND_LANG_ID'],
    $p['FIRST_WORD_ID'], $p['FIRST_LANG_ID'] );
  if( $in ) {
    print ' INSERTED REVERSE.';
  } else {
    print ' ERROR REVERSE.';
  }



  print '</p>';

}

print '</div>';
$this->page_footer();
