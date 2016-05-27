<?php
/*

Open Translation Engine (OTE)

*/
namespace Attogram;

define('OTE_VERSION', '1.0.0-dev');

/**
 * Open Translation Engine (OTE) class
 */  
class ote {
  
  public $db;

  /**
   * __construct()
   *
   * @param object $db
   * @return void
   */  
  function __construct( $db ) {
    $this->db = $db;
  }

  /**
   * get_dictionary_list()
   *
   * @param string $rel_url Optional. Relative URL of page
   * @return array List of dictionaries
   */
  function get_dictionary_list($rel_url='') {
    $sql = 'SELECT DISTINCT s_code, t_code FROM word2word ORDER BY s_code, t_code';
    $r = $this->db->query($sql);
    $dlist = array();
    $langs = $this->get_languages();
    foreach( $r as $d ) {
      $url = $rel_url . $d['s_code'] . '/' . $d['t_code'] . '/';
      $dlist[ $url ] = $langs[ $d['s_code'] ] . ' to ' . $langs[ $d['t_code'] ]; 
      $r_url = $rel_url . $d['t_code'] . '/' . $d['s_code'] . '/'; 
      if( !array_key_exists($r_url,$dlist) ) {
        $dlist[ $r_url ] = $langs[ $d['t_code'] ] . ' to ' . $langs[ $d['s_code'] ]; 
      }
    }
    asort($dlist);
    return $dlist;
  }

  /**
   * get_dictionary()
   *
   * @param string $s_code The Source Language Code
   * @param string $t_code The Target Language Code
   * @return array list of word pairs
   */
  function get_dictionary($s_code, $t_code) {
    $sql = '
    SELECT sw.word AS s_word, tw.word AS t_word
    FROM word2word AS ww,
         word AS sw,
         word AS tw
    WHERE ww.s_code = :s_code
    AND   ww.t_code = :t_code
    AND   sw.id = ww.s_id
    AND   tw.id = ww.t_id
    ORDER BY sw.word, tw.word
    ';
    $bind = array('s_code'=>$s_code, 't_code'=>$t_code);
    $r = $this->db->query($sql,$bind);

    $sql_r = '
    SELECT sw.word AS t_word, tw.word AS s_word
    FROM word2word AS ww,
         word AS sw,
         word AS tw
    WHERE ww.s_code = :t_code
    AND   ww.t_code = :s_code
    AND   sw.id = ww.s_id
    AND   tw.id = ww.t_id
    ORDER BY tw.word, sw.word
    ';  
    $r_r = $this->db->query($sql_r,$bind);

    $rv = array_merge($r, $r_r);
    
    $rv = $this->multiSort($rv, 's_word', 't_word');
    return $rv;
  }

  /**
   * get_language_name_from_code()
   *
   * @param string $code The Language Code
   * @param string $default Optional. The default language name to use & insert, if none found
   * @return string
   */
  function get_language_name_from_code($code, $default=FALSE) {
    $sql = 'SELECT language FROM language WHERE code = :code';
    $bind = array( 'code'=>$code);
    $r = $this->db->query($sql, $bind);
    if( isset($r[0]['language']) ) {
      return $r[0]['language'];
    }
    if( !$default ) { $default = $code; }
    // print '<p>Error: no language name found. code: ' . htmlentities($code) . '</p>';
    return $this->insert_language($code, $default);
  }

  /**
   * get_languages()
   *
   * @return array
   */
  function get_languages() {
    $sql = 'SELECT code, language FROM language ORDER by id';
    $r = $this->db->query($sql);
    if( !$r ) {
      return array();
    }
    $rv = array();
    foreach( $r as $g ) {
      $rv[ $g['code'] ] = $g['language'];
    }
    return $rv;
  }

  /**
   * get_all_words()
   *
   * @return array
   */
  function get_all_words() {
    $sql = 'SELECT word FROM word ORDER BY word';
    return $this->db->query($sql);   
  }

  /**
   * get_translation()
   *
   * @param string $word The Source Word
   * @param string $s_code The Source Language Code
   * @param string $t_code Optional. The Target Language Code
   * @return array
   */
  function get_translation($word, $s_code, $t_code='') {
    $and = $r_and = '';
    $bind = array();
    $sql = '
    SELECT sw.word AS s_word, word2word.s_code, tw.word AS t_word, word2word.t_code
    FROM word2word, word AS sw, word AS tw
    WHERE sw.word = :word AND sw.id = word2word.s_id AND tw.id = word2word.t_id
    ';
    $r_sql = '
    SELECT sw.word AS t_word, word2word.t_code AS s_code, tw.word AS s_word, word2word.s_code AS t_code
    FROM word2word, word AS sw, word AS tw
    WHERE tw.word = :word AND sw.id = word2word.s_id AND tw.id = word2word.t_id
    ';
    $bind['word'] = $word;

    if( $s_code && $t_code ) {
      $and = 'AND word2word.s_code = :s_code AND word2word.t_code = :t_code';
      $r_and = 'AND word2word.s_code = :t_code AND word2word.t_code = :s_code';
      $bind['s_code']=$s_code; $bind['t_code']=$t_code;
    } elseif ( $s_code && !$t_code ) {
      $and = 'AND word2word.s_code = :s_code';
      $r_and = 'AND word2word.t_code = :s_code';
      $bind['s_code']=$s_code;
    }

    $order = '';  // dev
    $sql .= "$and $order";
    $r_sql .= "$r_and $order";

    $r = $this->db->query($sql, $bind);
    $r_r = $this->db->query($r_sql, $bind);

    $r = array_merge($r,$r_r);
    $r = $this->multiSort($r, 's_code', 't_code', 's_word', 't_word');
    return $r;
  }

  /**
   * get_id_from_word()
   *
   * @param string $word The Source Word
   * @return int
   */
  function get_id_from_word($word) {
    $sql = 'SELECT id FROM word WHERE word = :word';
    $bind=array('word'=>$word);
    $r = $this->db->query($sql, $bind);
    if( !$r || !isset($r[0]) || !isset($r[0]['id']) ) {
      //print '<p>ERROR: no word.id found.  Inserting word: ' . $word . '</p>';
      return $this->insert_word($word);
    }
    return $r[0]['id'];
  }

  /**
   * insert_word()
   */
  function insert_word($word) {
    $sql = 'INSERT INTO word (word) VALUES (:word)';
    $bind=array('word'=>$word);
    $r = $this->db->queryb($sql, $bind);
    if( !$r ) {
      print '<p>ERROR: can not insert word</p>';
      return 0;
    }
    return $this->db->db->lastInsertId();
  }

  /**
   * insert_language()
   */
  function insert_language($code, $language_name) {
    $sql = 'INSERT INTO language (code, language) VALUES (:code, :language)';
    $bind=array('code'=>$code, 'language'=>$language_name);
    $r = $this->db->queryb($sql, $bind);
    if( !$r ) {
      print '<p>ERROR: can not insert language</p>';
      return 'error';
    }
    return $language_name;
  }

  /**
   * multiSort()
   */
  function multiSort() {
    $args = func_get_args();
    $c = count($args);
    if ($c < 2) {
      return false;
    }
    $array = array_splice($args, 0, 1);
    $array = $array[0];
    usort($array, function($a, $b) use($args) {
      $i = 0;
      $c = count($args);
      $cmp = 0;
      while($cmp == 0 && $i < $c){
        //$cmp = strcmp($a[ $args[ $i ] ], $b[ $args[ $i ] ]);
        $cmp = strcasecmp( mb_strtolower($a[ $args[$i] ]), mb_strtolower($b[ $args[$i] ]) );
        $i++;
      }
      return $cmp;
    });
    return $array;
  }

} // end class ote
