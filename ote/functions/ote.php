<?php
/*
 * The Open Translation Engine (OTE)
*/
namespace Attogram;

define('OTE_VERSION', '1.0.0-dev05');

/**
 * Open Translation Engine (OTE) class
 */
class ote {

  public $db;
  public $log;
  public $normalized_language_pairs;
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
  function insert_language( string $code, string $name ) {
    $sql = 'INSERT INTO language (code, name) VALUES (:code, :name)';
    $bind=array('code'=>$code, 'name'=>$name);
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
    $sql = 'SELECT id, code, name FROM language ORDER by id';
    $r = $this->db->query($sql);
    if( !$r ) {
      $this->log->error('get_languages: Languages Not Found, or Query Failed');
      return $this->languages;
    }
    foreach( $r as $g ) {
      $this->languages[ $g['code'] ] = array( 'id'=>$g['id'], 'name'=>$g['name'] );
    }
    $this->log->debug('get_languages: got ' . sizeof($this->languages)
      . ' languages', array_keys($this->languages));
    return $this->languages;
  } // end function get_languages()

  /**
   * get_language_code_from_id()
   * @param  integer  $id  The Language ID
   * @return string        The Language Code, or FALSE
   */
  function get_language_code_from_id( int $id ) {
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
   * get_language_id_from_code()
   * @param  string   $code  The Language Code
   * @return integer         The Language ID, or FALSE
   */
  function get_language_id_from_code( string $code ) {
    $langs = $this->get_languages();
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        $this->log->debug('get_language_id_from_code: code=' . $code . ' id=' . $lang['id'] );
        return $lang['id'];
      }
    }
    $this->log->error('get_language_id_from_code: code=' . $code . ' Not Found');
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
  function get_language_name_from_code( string $code, string $default='' ) {
    $this->log->debug('get_language_name_from_code: code='
      . htmlentities($code) . ' default=' . htmlentities($default));
    $langs = $this->get_languages();
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        $this->log->debug('get_language_name_from_code: code='
          . htmlentities($code) . ' name=' . htmlentities($lang['name']));
        return $lang['name'];
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
  function get_dictionary_list( string $rel_url='' ) {
    $this->log->debug('get_dictionary_list: rel_url=' . $rel_url
      . ' backtrace=' . debug_backtrace()[1]['function'] );
    $sql = 'SELECT DISTINCT sl, tl FROM word2word';
    $r = $this->db->query($sql);
    $dlist = array();
    $langs = $this->get_languages();
    foreach( $r as $d ) {
      $s_code = $this->get_language_code_from_id( $d['sl'] );
      $t_code = $this->get_language_code_from_id( $d['tl'] );
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
  function insert_word( string $word ) {
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
  function get_id_from_word( string $word ) {
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
   * @param  integer $sw   Source Word ID
   * @param  integer $sl   Source Language ID
   * @param  integer $tw   Target Word ID
   * @param  integer $tl   Target Language ID
   * @return integer       Inserted record ID, or FALSE
   */
  function insert_word2word( int $sw, int $sl, int $tw, int $tl ) {
    $bind = array('sw'=>$sw, 'sl'=>$sl, 'tw'=>$tw, 'tl'=>$tl);
    $this->log->debug('insert_word2word', $bind);
    $sql = 'INSERT INTO word2word ( sw, sl, tw, tl ) VALUES ( :sw, :sl, :tw, :tl )';
    $r = $this->db->queryb($sql, $bind);
    if( $r ) {
      $id = $this->db->db->lastInsertId();
      $this->log->debug("insert_word2word: inserted. id=$id");
      return $id;
    }
    if( $this->db->db->errorCode() == '0000' ) {
      $this->log->notice('insert_word2word: Insert failed: duplicate entry.');
    } else {
      $this->log->error('insert_word2word: can not insert. errorInfo: '
        . print_r($this->db->db->errorInfo(),1) );
    }
    return FALSE;
  }

  /**
   * get_word2word()
   * @param  integer $sw   Source Word ID
   * @param  integer $sl   Source Language ID
   * @param  integer $tw   Target Word ID
   * @param  integer $tl   Target Language ID
   * @return boolean       TRUE if word2word entry exists, else FALSE
   */
  function get_word2word( int $sw, int $sl, int $tw, int $tl ) {
    $bind = array('sw'=>$sw, 'sl'=>$sl, 'tw'=>$tw, 'tl'=>$tl);
    $this->log->debug('get_word2word', $bind);
    $sql = 'SELECT sw FROM word2word WHERE sw=:sw AND sl=:sl AND tw=:tw AND tl=:tl';
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
   * @param  integer $sl   Source Language ID
   * @param  integer $tl   Target Language ID
   * @return array         list of word pairs
   */
  function get_dictionary( int $sl, int $tl ) {
    $this->log->debug("get_dictionary: sl=$sl tl=$tl");
    list($sl_norm,$tl_norm) = $this->normalize_language_pair($sl,$tl);
    if( ($sl_norm==$sl) && ($tl_norm==$tl) ) {
      $select = 'sw.word AS s_word, tw.word AS t_word';
      $order ='ORDER BY sw.word COLLATE NOCASE, tw.word COLLATE NOCASE';
    } else {
      $select = 'sw.word AS t_word, tw.word AS s_word';
      $order ='ORDER BY tw.word COLLATE NOCASE, sw.word COLLATE NOCASE';
      $sl = $sl_norm;
      $tl = $tl_norm;
    }
    $sql = "SELECT $select FROM word2word AS ww, word AS sw, word AS tw
    WHERE ww.sl = :sl AND ww.tl = :tl
    AND sw.id = ww.sw AND tw.id = ww.tw $order";
    $bind = array('sl'=>$sl, 'tl'=>$tl);
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
  function get_translation( string $word, string $s_code, string $t_code='' ) {

    // TODO list($s,$t) = $this->normalize_language_pair($s_code,$t_code);
    // TODO: comp/merge with get_dictionary()

    $and = $r_and = '';
    $bind = array();
    $sql = '
    SELECT sw.word AS s_word, word2word.sl, tw.word AS t_word, word2word.tl
    FROM word2word, word AS sw, word AS tw
    WHERE sw.word = :word AND sw.id = word2word.sw AND tw.id = word2word.tw
    ';
    $r_sql = '
    SELECT sw.word AS t_word, word2word.tl AS s_code, tw.word AS s_word, word2word.sl AS t_code
    FROM word2word, word AS sw, word AS tw
    WHERE tw.word = :word AND sw.id = word2word.sw AND tw.id = word2word.tw
    ';
    $bind['word'] = $word;

    if( $s_code && $t_code ) {
      $and = 'AND word2word.sl = :s_code AND word2word.tl = :t_code';
      $r_and = 'AND word2word.sl = :t_code AND word2word.tl = :s_code';
      $bind['s_code']=$s_code; $bind['t_code']=$t_code;
    } elseif ( $s_code && !$t_code ) {
      $and = 'AND word2word.sl = :s_code';
      $r_and = 'AND word2word.tl = :s_code';
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
   * search()
   *
   * @param string $q Search String
   */
  function search( string $q ) {
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
   * @param  integer $sl   Source Language ID
   * @param  integer $tl   Target Language ID
   * @return array         An array of the normalized order (source_lang_id, target_lang_id)
   */
  function normalize_language_pair( int $sl, int $tl ) {
    $nlp_norm = $sl . '-' . $tl;
    $nlp_rev  = $tl . '-' . $sl;
    if( isset($this->normalized_language_pairs[$nlp_norm]) ) {
      return $this->normalized_language_pairs[$nlp_norm];
    }
    // lookup any source=sl, target=tl entries in word2word
    $sql = 'SELECT count(sl) AS count FROM word2word WHERE sl = :sl AND tl = :tl';
    $bind = array( 'sl'=>$sl, 'tl'=>$tl );
    $norm = $this->db->query($sql,$bind);
    if( $norm ) { $norm = $norm[0]['count']; } else { $norm = 0; }
    // lookup any source=tl, target=sl entries in word2word
    $bind = array( 'tl'=>$sl, 'sl'=>$tl );
    $rev = $this->db->query($sql,$bind);
    if( $rev ) { $rev = $rev[0]['count']; } else { $rev = 0; }
    if( $norm && !$rev ) { // only normal form exists, use normal
      return $this->normalized_language_pairs[$nlp_norm]
        = $this->normalized_language_pairs[$nlp_rev]
        = array($sl,$tl);
    } elseif( !$norm && $rev ) { // only reverse form exists, use reverse
      return $this->normalized_language_pairs[$nlp_norm]
        = $this->normalized_language_pairs[$nlp_rev]
        = array($tl,$sl);
    } elseif( !$norm && !$rev ) { // nothing exists, use normal
      return $this->normalized_language_pairs[$nlp_norm]
        = $this->normalized_language_pairs[$nlp_rev]
        = array($sl,$tl);
    } else { // both normal and reverse exists -- ERROR!
      if( $norm >= $rev ) {
        $dn = array( $sl, $tl ); // norm has same amount or more entries, use norm
      } else {
        $dn = array( $tl, $sl ); // reverse has more entries, use rev
      }
      return $this->normalized_language_pairs[$nlp_norm]
        = $this->normalized_language_pairs[$nlp_rev]
        = $this->normalize_word2word_table( $dn[0], $dn[1] );
    }
  } // end function normalize_language_pair()

  /**
   * normalize_word2word_table()
   * update word2word table to use only one form of SOURCE-TARGET
   * @param  integer $sl   Source Language ID
   * @param  integer $tl   Target Language ID
   * @return array         An array of the normlized order (source_lang_id, target_lang_id)
   */
  function normalize_word2word_table( int $sl, int $tl ) {
    $sql = 'UPDATE word2word SET sl=tl, tl=sl, sw=tw, tw=sw WHERE sl=:sl AND tl=:tl';
    $bind = array( 'sl'=>$tl, 'tl'=>$sl );
    $r = $this->db->queryb($sql,$bind);
    if( !$r ) {
      $this->log->error("normalize_word2word_table: FAILED: sl=$sl tl=$tl");
    } else {
      $this->log->debug("normalize_word2word_table: OK sl=$sl tl=$tl");
    }
    return array($sl, $tl);
  }

  /**
   * do_import()
   * @param string $w   List of word pairs, 1 pair to a line, with \n at end of line
   * @param string $d   Deliminator
   * @param string $s   Source Language Code
   * @param string $t   Target Language Code
   * @param string $sn  (optional) Source Language Name
   * @param string $tn  (optional) Target Language Name
   */
  function do_import( string $w, string $d, string $s, string $t, string $sn='', string $tn='' ) {

    $this->log->debug("do_import: s=$s t=$t d=$d sn=$sn tn=$tn w strlen=" . strlen($w));

    $d = str_replace('\t', "\t", $d); // allow real tabs

    $sn = $this->get_language_name_from_code($s, $default=$sn);
    if( !$sn ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $si = $this->get_language_id_from_code($s);
    if( !$si ) {
      $error = 'Error: can not get source language ID';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $tn = $this->get_language_name_from_code($t, $default=$tn);
    if( !$tn ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $ti = $this->get_language_id_from_code($t);
    if( !$si ) {
      $error = 'Error: can not get target language ID';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $this->log->debug("do import: sn=$sn si=$si tn=$tn ti=$ti");

    list($si,$ti) = $this->normalize_language_pair($si,$ti);

    $lines = explode("\n", $w);

    print '<div class="container">'
    . 'Source Language: ID: <code>' . $si . '</code>'
    . ' Code: <code>' . htmlentities($s) . '</code>'
    . ' Name: <code>' . htmlentities($sn) . '</code>'
    . '<br />Target Language:&nbsp; ID: <code>' . $ti  . '</code>'
    . ' Code: <code>' . htmlentities($t) . '</code>'
    . ' Name: <code>' . htmlentities($tn) . '</code>'
    . '<br />Deliminator: <code>' . htmlentities($d) . '</code>'
    . '<br />Lines: <code>' . sizeof($lines) . '</code><hr /><small>'
    ;

    $line_count = $import_count = $error_count = $skip_count = $dupe_count = 0;

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

      $sw = trim($wp[0]); // The Source Word
      if( !$sw ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing source word</p>';
        $error_count++; $skip_count++;
        continue;
      }
      $swi = $this->get_id_from_word($sw);

      $tw = trim($wp[1]); // The Target Word
      if( !$tw ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing target word</p>';
        $error_count++; $skip_count++;
        continue;
      }
      $twi = $this->get_id_from_word($tw);

      $this->log->debug("do_import: sw=$sw swi=$swi si=$si tw=$tw twi=$twi ti=$ti");

      $bind = array( 'sw'=>$si, 'sl'=>$si, 'tw'=>$ti, 'tl'=>$ti );

      // check if REVERSE pair already exists...
      $check = $this->get_word2word( $swi, $si, $twi, $ti );
      if( $check ) {
        //print '<p>Info: Line #' . $line_count . ': Duplicate (reverse). Skipping line';
        $error_count++; $dupe_count++; $skip_count++;
        continue;
      }

      $r = $this->insert_word2word( $swi, $si, $twi, $ti );
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

} // end class ote
