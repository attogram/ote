<?php

// OTE global things

define('OTE_VERSION', '1.0.0-dev');

/**
 * get_dictionary_list()
 */
function get_dictionary_list($db, $rel_url='') {
  $sql = 'SELECT DISTINCT s_code, t_code FROM word2word ORDER BY s_code, t_code';
  $r = $db->query($sql);
  $dlist = array();
  $langs = get_languages($db);
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
 * get_id_from_word()
 */
function get_id_from_word($word, $db) {
  $sql = 'SELECT id FROM word WHERE word = :word';
  $bind=array('word'=>$word);
  $r = $db->query($sql, $bind);
  if( !$r || !isset($r[0]) || !isset($r[0]['id']) ) {
    //print '<p>ERROR: no word.id found.  Inserting word: ' . $word . '</p>';
    return insert_word($word, $db);
  }
  return $r[0]['id'];
}

/**
 * insert_word()
 */
function insert_word($word, $db) {
  $sql = 'INSERT INTO word (word) VALUES (:word)';
  $bind=array('word'=>$word);
  $r = $db->queryb($sql, $bind);
  if( !$r ) {
    print '<p>ERROR: can not insert word</p>';
    return 0;
  }
  return $db->db->lastInsertId();
}

/**
 * get_language_name_from_code()
 */
function get_language_name_from_code($code, $db, $default=FALSE) {
  $sql = 'SELECT language FROM language WHERE code = :code';
  $bind = array( 'code'=>$code);
  $r = $db->query($sql, $bind);
  if( isset($r[0]['language']) ) {
    return $r[0]['language'];
  }
  if( !$default ) { $default = $code; }
  // print '<p>Error: no language name found. code: ' . htmlentities($code) . '</p>';
  return insert_language($code, $default, $db);
}

/**
 * insert_language()
 */
function insert_language($code, $language_name, $db) {
  $sql = 'INSERT INTO language (code, language) VALUES (:code, :language)';
  $bind=array('code'=>$code, 'language'=>$language_name);
  $r = $db->queryb($sql, $bind);
  if( !$r ) {
    print '<p>ERROR: can not insert language</p>';
    return 'error';
  }
  return $language_name;
}

/**
 * get_languages()
 */
function get_languages($db) {
  $sql = 'SELECT code, language FROM language ORDER by id';
  $r = $db->query($sql);
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
 * get_dictionary()
 */
function get_dictionary( $s_code, $t_code, $db) {
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
  $r = $db->query($sql,$bind);

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
  $r_r = $db->query($sql_r,$bind);

  $rv = array_merge($r, $r_r);
  
  $rv = multiSort($rv, 's_word', 't_word');
  return $rv;
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
      $cmp = strcmp( strtolower($a[ $args[ $i ] ]), strtolower($b[ $args[ $i ] ]));
      $i++;
    }
    return $cmp;
  });
  return $array;
}
