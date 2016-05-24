<?php

// OTE functions

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
function get_language_name_from_code($code, $db) {
  $sql = 'SELECT language FROM language WHERE code = :code';
  $bind = array( 'code'=>$code);
  $r = $db->query($sql, $bind);
  if( isset($r[0]['language']) ) {
    return $r[0]['language'];
  }
  //print '<p>Error: no language name found. code: ' . htmlentities($code) . '</p>';
  return insert_language($code, $code, $db);
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