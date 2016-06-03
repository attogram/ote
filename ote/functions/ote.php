<?php
/*
 * Open Translation Engine (OTE)
 * powered by Attogram Framework
*/
namespace Attogram;

define('OTE_VERSION', '1.0.0-dev04');

/**
 * Open Translation Engine (OTE) class
 */
class ote {

  public $db, $log, $normalized_language_pairs;
  public $languages;

  /**
   * __construct()
   * @param object $db  Attogram PDO database object
   * @param object $log  PSR-3 compliant logger
   * @return void
   */
  function __construct($db, $log) {
    $this->db = $db;
    $this->log = $log;
    $this->log->debug('START OTE v' . OTE_VERSION);
  }

  /**
   * insert_language()
   * @param string $code The Language Code
   * @param string $language_name The Language Name
   * @return integer ID of the new language, or FALSE
   */
  function insert_language($code, $name) {
    $sql = 'INSERT INTO language (code, language) VALUES (:code, :language)';
    $bind=array('code'=>$code, 'language'=>$name);
    $r = $this->db->queryb($sql, $bind);
    if( !$r ) {
      $this->log->error('insert_language: can not insert language');
      return FALSE;
    }
    $id = $this->db->db->lastInsertId();
    $this->log->debug('insert_language: inserted id=' . $id
      . ' code=' . htmlentities($code) . ' name=' . htmlentities($name));
    return $id;
  }

  /**
   * get_languages() - get a list of all languages
   * @uses $languages
   * @return array
   */
  function get_languages() {
    //$this->log->debug('get_languages: backtrace=' . debug_backtrace()[1]['function'] );
    if( isset($this->languages) && is_array($this->languages) ) {
      return $this->languages;
    }
    $this->languages = array();
    $sql = 'SELECT id, code, language FROM language ORDER by id';
    $r = $this->db->query($sql);
    if( !$r ) {
      $this->log->error('get_languages: Languages Not Found, or Query Failed');
      return $this->languages;
    }
    foreach( $r as $g ) {
      $this->languages[ $g['code'] ] = array( 'id'=>$g['id'], 'language'=>$g['language'] );
    }
    $this->log->debug('get_languages: got ' . sizeof($this->languages)
      . ' languages', array_keys($this->languages));
    return $this->languages;
  } // end function get_languages()

  /**
   * get_language_code_from_id
   * @param integer $id The Language ID
   * @return string The Language Code, or FALSE
   */
  function get_language_code_from_id($id) {
    //$this->log->debug('get_language_code_from_id: id=' . $id
    //  . ' backtrace=' . debug_backtrace()[1]['function'] );
    $langs = $this->get_languages();
    foreach( $langs as $code => $lang ) {
      if( $lang['id'] == $id ) {
        $this->log->debug('get_language_code_from_id: id=' . $id . ' code=' . $code );
        return $code;
      }
    }
    $this->log->error('get_language_code_from_id: id=' . $id . ' Not Found');
    return FALSE;
  } // end function get_language_code_from_id()

  /**
   * get_language_name_from_code() - Gets a Language Name.
   * If the language is not found, inserts the language into the database.
   *
   * @param string $code    The Language Code
   * @param string $default (optional) The default language name to use & insert, if none found
   * @return string   The Language Name, or FALSE
   */
  function get_language_name_from_code($code, $default=FALSE) {
    $this->log->debug('get_language_name_from_code: code='
      . htmlentities($code) . ' default=' . htmlentities($default));
    $langs = $this->get_languages();
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        $this->log->debug('get_language_name_from_code: code='
          . htmlentities($code) . ' name=' . htmlentities($lang['language']));
        return $lang['language'];
      }
    }
    $this->log->error('get_language_name_from_code: Not Found.  Attempting insert:');
    if( !$default ) {
      $default = $code;
    }
    $lang_id = $this->insert_language($code, $default);
    if( !$lang_id ) {
      $this->log->error('get_language_name_from_code: Can not insert language.');
      return FALSE;
    }
    return $default;
  }

  /**
   * get_dictionary_list()
   *
   * @param string $rel_url Optional. Relative URL of page
   * @return array List of dictionaries
   */
  function get_dictionary_list($rel_url='') {
    $this->log->debug('get_dictionary_list: rel_url=' . $rel_url
      . ' backtrace=' . debug_backtrace()[1]['function'] );
    $sql = 'SELECT DISTINCT s_code_id, t_code_id FROM word2word';
    $r = $this->db->query($sql);
    $dlist = array();
    $langs = $this->get_languages();
    foreach( $r as $d ) {
      $this->log->debug("loop d=",$d);
      $s_code = $this->get_language_code_from_id( $d['s_code_id'] );
      $t_code = $this->get_language_code_from_id( $d['t_code_id'] );
      $url = $rel_url . $s_code . '/' . $t_code . '/';
      $dlist[ $url ] = $langs[$s_code] . ' to ' . $langs[$t_code];
      $r_url = $rel_url . $t_code . '/' . $s_code . '/';
      if( !array_key_exists($r_url,$dlist) ) {
        $dlist[ $r_url ] = $langs[$t_code] . ' to ' . $langs[$s_code];
      }
    }
    asort($dlist);
    $this->log->debug('get_dictionary_list: got ' . sizeof($dlist)
      . ' dictionaries', array_keys($dlist));
    return $dlist;
  } // end function get_dictionary_list()

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
   * get_id_from_word()
   *
   * @param string $word The Source Word
   * @return int
   */
  function get_id_from_word($word) {
    $sql = 'SELECT id FROM word WHERE word = :word LIMIT 1';
    $bind=array('word'=>$word);
    $r = $this->db->query($sql, $bind);
    if( !$r || !isset($r[0]) || !isset($r[0]['id']) ) {
      //print '<p>ERROR: no word.id found.  Inserting word: ' . $word . '</p>';
      return $this->insert_word($word);
    }
    return $r[0]['id'];
  }

  /**
   * get_all_words()
   *
   * @return array
   */
  function get_all_words() {
    $sql = 'SELECT word FROM word ORDER BY word COLLATE NOCASE';
    //$limit = ' LIMIT 0,100'; // dev
    //$sql .= $limit;
    return $this->db->query($sql);
  }

  /**
   * insert_word2word()
   * @param integer $s_id Source Word ID
   * @param integer $s_code_id Source Language ID
   * @param integer $t_id Target Word ID
   * @param integer $t_code_id Target Language ID
   * @return interger Inserted record ID, or FALSE
   */
  function insert_word2word( $s_id, $s_code_id, $t_id, $t_code_id ) {
    $bind = array('s_id'=>$s_id, 's_code_id'=>$s_code_id, 't_id'=>$t_id, 't_code_id'=>$t_code_id);
    $this->log->debug('insert_word2word', $bind);
    $sql = '
      INSERT INTO word2word (
        s_id, s_code_id, t_id, t_code_id
      ) VALUES (
        :s_id, :s_code_id, :t_id, :t_code_id
      )';
    $r = $this->db->queryb($sql, $bind);
    if( $r ) {
      $this->log->debug("insert_word2word: OK $s_id:$s_code_id:$t_id:$t_code_id");
      return $this->db->db->lastInsertId();
    }
    if( $this->db->db->errorCode() == '0000' ) {
      $this->log->notice('insert_word2word: Insert failed: duplicate entry.');
    } else {
      $this->log->error('insert_word2word: can not insert. errorInfo: ' . print_r($this->db->db->errorInfo(),1) );
    }
    return FALSE;
  }

  /**
   * get_word2word()
   * @param integer $s_id Source Word ID
   * @param integer $s_code_id Source Language ID
   * @param integer $t_id Target Word ID
   * @param integer $t_code_id Target Language ID
   * @return boolean
   */
  function get_word2word( $s_id, $s_code_id, $t_id, $t_code_id ) {
    $bind = array('s_id'=>$s_id, 's_code_id'=>$s_code_id, 't_id'=>$t_id, 't_code_id'=>$t_code_id);
    $this->log->debug('get_word2word', $bind);
    $sql = '
      SELECT s_id
      FROM word2word
      WHERE s_id = :t_id
      AND t_id = :s_id
      AND s_code_id = :t_code_id
      AND t_code_id = :s_code_id
      LIMIT 1';
    $r = $this->db->query($sql,$bind);
    if( $r ) {
      $this->log->debug('get_word2word: exists');
      return TRUE;
    } else {
      $this->log->debug('get_word2word: does not exist');
      return FALSE;
    }
  }

  /**
   * get_dictionary()
   *
   * @param string $s_code The Source Language Code
   * @param string $t_code The Target Language Code
   *
   * @return array list of word pairs
   */
  function get_dictionary($s_code, $t_code) {
    list($s_code_norm,$t_code_norm) = $this->normalize_language_pair($s_code,$t_code);
    if( ($s_code_norm==$s_code) && ($t_code_norm==$t_code) ) {
      $select = 'sw.word AS s_word, tw.word AS t_word';
      $order ='ORDER BY sw.word COLLATE NOCASE, tw.word COLLATE NOCASE';
    } else {
      $select = 'sw.word AS t_word, tw.word AS s_word';
      $order ='ORDER BY tw.word COLLATE NOCASE, sw.word COLLATE NOCASE';
      $s_code = $s_code_norm;
      $t_code = $t_code_norm;
    }
    $sql = "SELECT $select FROM word2word AS ww, word AS sw, word AS tw
    WHERE ww.s_code = :s_code AND ww.t_code = :t_code
    AND sw.id = ww.s_id AND tw.id = ww.t_id $order";
    $bind = array('s_code'=>$s_code, 't_code'=>$t_code);
    $r = $this->db->query($sql,$bind);
    return $r;
  }

  /**
   * get_translation()
   *
   * @param string $word The Source Word
   * @param string $s_code The Source Language Code
   * @param string $t_code Optional. The Target Language Code
   *
   * @return array list of word pairs
   */
  function get_translation($word, $s_code, $t_code='') {

    // TODO list($s,$t) = $this->normalize_language_pair($s_code,$t_code);
    // TODO: comp/merge with get_dictionary()

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

    $limit = ' LIMIT 0,100';  // dev
    $sql .= "$and $limit";
    $r_sql .= "$r_and $limit";

    $r = $this->db->query($sql, $bind);
    $r_r = $this->db->query($r_sql, $bind);

    $r = array_merge($r,$r_r);
    $r = $this->multiSort($r, 's_code', 't_code', 's_word', 't_word');
    return $r;
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

  /**
   * do_import()
   *
   * @param string $w List of word pairs
   * @param string $d Deliminator
   * @param string $s Source Language Code
   * @param string $t Target Language Code
   * @param string $sn Optional. Source Language Name
   * @param string $tn Optional. Target Language Name
   *
   */
  function do_import( $w, $d, $s, $t, $sn='', $tn='' ) {

    $this->log->debug("do_import: s=$s t=$t");
    $d = str_replace('\t', "\t", $d); // allow real tabs
    $sn = $this->get_language_name_from_code($s, $default=$sn);
    if( !$sn ) { print 'Error: can not get/set source language'; return; }
    $tn = $this->get_language_name_from_code($t, $default=$tn);
    if( !$tn ) { print 'Error: can not get/set target language'; return; }

    //$this->log->debug('do_import: sn.id=' . $sn['id'] . ', sn:' . print_r($sn,1));
    //$this->log->debug('do_import: tn.id=' . $tn['id'] . ', tn:' . print_r($tn,1));
    $lines = explode("\n", $w);
    print '<div class="container">';
    print 'Source Language: ID: <code>' . $sn['id'] . '</code> Code: <code>' . htmlentities($s) . '</code> Name: <code>' . htmlentities($sn['code']) . '</code><br />';
    print 'Target Language: ID: <code>' . $tn['id'] . '</code> Code: <code>' . htmlentities($t) . '</code> Name: <code>' . htmlentities($tn['code']) . '</code><br />';
    print 'Deliminator: <code>' . htmlentities($d) . '</code><br />';
    print 'Lines: <code>' . sizeof($lines) . '</code><hr /><small>';

    $line_count = 0;
    $import_count = 0;
    $error_count = 0;
    $skip_count = 0;
    $dupe_count = 0;

    foreach($lines as $line) {

      set_time_limit(240);

      $line_count++;
      $line = trim($line);

      if( $line == '' ) {
        //print '<p>Info: Line #' . $line_count . ': Blank line found. Skipping line</p>';
        $skip_count++;
        continue;
      }

      if( preg_match('/^#/', $line) ) {
        //print '<p>Info: Line #' . $line_count . ': Comment line found. Skipping line.</p>';
        $skip_count++;
        continue;
      }

      if( !preg_match('/' . $d . '/', $line) ) {
        print '<p>Error: Line #' . $line_count . ': Deliminator (' .htmlentities($d) . ') Not Found. Skipping line.</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $wp = explode($d, $line);

      if( sizeof($wp) != 2 ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Expecting 2 words, found ' . sizeof($wp) . ' words</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $sw = trim($wp[0]);
      if( !$sw ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing source word</p>';
        $error_count++; $skip_count++;
        continue;
      }
      $tw = trim($wp[1]);
      if( !$tw ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing target word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $si = $this->get_id_from_word($sw);
      $ti = $this->get_id_from_word($tw);

      list($s,$t) = $this->normalize_language_pair($s,$t);

      $bind = array( 's_id'=>$si, 's_code_id'=>$sn['id'], 't_id'=>$ti, 't_code_id'=>$tn['id']);

      // check if REVERSE pair already exists...
      $check = $this->get_word2word( $si, $sn['id'], $ti, $tn['id'] );
      if( $check ) {
        //print '<p>Info: Line #' . $line_count . ': Duplicate (reverse). Skipping line';
        $error_count++; $dupe_count++; $skip_count++;
        continue;
      }

      $r = $this->insert_word2word( $si, $sn['id'], $ti, $tn['id'] );
      if( !$r ) {
        if( $this->db->db->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error.'
        . ' sw: ' . htmlentities($sw)
        . ' tw: ' . htmlentities($tw)
        . ' Bind: ' . print_r($bind,1)
        . ' errorInfo: ' . print_r($this->db->db->errorInfo(),1)
        . '</p>';
        $error_count++; $skip_count++;
      } else {
        $import_count++;

        if( $line_count % 100 == 0 ) {
          print ' ' . $line_count . ' ';
          @ob_flush(); flush();
        } elseif( $line_count % 10 == 0 ) {
          print '.';
          @ob_flush(); flush();
        }

      }

    } // end foreach line

    print '</small><hr />';
    print '<code>' . $import_count . '</code> translations imported.<br />';
    print '<code>' . $error_count . '</code> errors.<br />';
    print '<code>' . $dupe_count . '</code> duplicates/existing.<br />';
    print '<code>' . $skip_count . '</code> lines skipped.<br />';
    print '</div>';

  } // end do_import

  /**
   * search()
   *
   * @param string $q Search String
   */
  function search($q) {
    $r = '';
    $r .= '<p>search: ' . htmlentities($q) . '</p>';
    $s_code = 'eng';
    $t_code = '';
    $t = $this->get_translation($q, $s_code, $t_code);
    $r .=  '<pre>' . print_r($t,1) . '</pre>';
    return $r;
  }

  /**
   * normalize_language_pair()
   *
   * @param string $s_code Source Language Code
   * @param string $t_code Target Language Code
   *
   * @return array An array of the normalized order (source_code, language_code)
   */
  function normalize_language_pair( $s_code, $t_code ) {
    $nlp_norm = $s_code . '-' . $t_code;
    $nlp_rev  = $t_code . '-' . $s_code;
    if( isset($this->normalized_language_pairs[$nlp_norm]) ) {
      return $this->normalized_language_pairs[$nlp_norm];
    }
    // lookup any source=s_code, target=t_code entries in word2word
    $sql = 'SELECT count(s_id) AS count FROM word2word WHERE s_code = :s_code AND t_code = :t_code';
    $bind = array( 's_code'=>$s_code, 't_code'=>$t_code );
    $norm = $this->db->query($sql,$bind);
    if( $norm ) { $norm = $norm[0]['count']; } else { $norm = 0; }
    // lookup any source=t_code, target=s_code entries in word2word
    $bind = array( 't_code'=>$s_code, 's_code'=>$t_code );
    $rev = $this->db->query($sql,$bind);
    if( $rev ) { $rev = $rev[0]['count']; } else { $rev = 0; }
    if( $norm && !$rev ) { // only normal form exists, use normal
      return $this->normalized_language_pairs[$nlp_norm] = $this->normalized_language_pairs[$nlp_rev]
        = array($s_code,$t_code);
    } elseif( !$norm && $rev ) { // only reverse form exists, use reverse
      return $this->normalized_language_pairs[$nlp_norm] = $this->normalized_language_pairs[$nlp_rev]
        = array($t_code,$s_code);
    } elseif( !$norm && !$rev ) { // nothing exists, use normal
      return $this->normalized_language_pairs[$nlp_norm] = $this->normalized_language_pairs[$nlp_rev]
        = array($s_code,$t_code);
    } else { // both normal and reverse exists -- ERROR!
      if( $norm >= $rev ) {
        $dn = array( $s_code, $t_code ); // norm has same amount or more entries, use norm
      } else {
        $dn = array( $t_code, $s_code ); // reverse has more entries, use rev
      }
      return $this->normalized_language_pairs[$nlp_norm] = $this->normalized_language_pairs[$nlp_rev]
        = $this->normalize_word2word_table( $dn[0], $dn[1] );
    }
  } // end function normalize_language_pair()

  /**
   * normalize_word2word_table()
   * update word2word table to use only one form of SOURCE-TARGET
   *
   * @param string $s_code Source Language Code
   * @param string $t_code Target Language Code
   *
   * @return array An array of the normlized order (source_code, language_code)
   */
  function normalize_word2word_table( $s_code, $t_code ) {
    // find all reverse entries:  where s_code=$t_code and t_code=$s_code
    //  update reverse entries to normal: switch s_id/t_id and switch s_code/t_code
    $sql = 'UPDATE word2word
    SET s_code = t_code, t_code = s_code, s_id = t_id, t_id = s_id
    WHERE s_code = :s_code AND t_code = :t_code';
    $bind = array( 's_code'=>$t_code, 't_code'=>$s_code );
    $r = $this->db->queryb($sql,$bind);
    if( !$r ) {
      print "<pre>ERROR: normalize_word2word_table( $s_code, $t_code )</pre>";
    }
    return array($s_code, $t_code);
  }

} // end class ote
