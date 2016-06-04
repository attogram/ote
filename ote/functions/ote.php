<?php
/*
 * The Open Translation Engine (OTE)
*/
namespace Attogram;

define('OTE_VERSION', '1.0.0-dev08');

/**
 * Open Translation Engine (OTE) class
 */
class ote {

  public $db;
  public $log;
  public $languages;

  /**
   * __construct()
   * @param  object $db   Attogram PDO database object
   * @param  object $log  PSR-3 compliant logger
   * @return void
   */
  function __construct($db, $log) {
    $this->db = $db;
    $this->log = $log;
    $this->log->debug('START OTE v' . OTE_VERSION);
  }

  /**
   * insert_language()
   * @param  string $code   The Language Code
   * @param  string $name   The Language Name
   * @return int            ID of the new language, or FALSE
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
    unset($this->languages); // reset the language list
    $langs = $this->get_languages();
    return $id;
  }

  /**
   * get_languages() - get a list of all languages
   * @return array
   */
  function get_languages() {
    $this->log->debug('get_languages: backtrace=' . debug_backtrace()[1]['function'] );
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
    $this->log->debug('get_languages: got ' . sizeof($this->languages). ' languages');
    //, array_keys($this->languages));
    return $this->languages;
  } // end function get_languages()

  /**
   * get_language_code_from_id()
   * @param  int     $id  The Language ID
   * @return string       The Language Code, or FALSE
   */
  function get_language_code_from_id( int $id ) {
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
   * @param string  $code  The Language Code
   * @param int            The Language ID, or FALSE
   */
  function get_language_id_from_code( string $code ) {
    $langs = $this->get_languages();
    if( !$langs ) {
      $this->log->error('get_language_id_from_code: no languages found');
      return FALSE;
    }
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        $this->log->debug('get_language_id_from_code: code=' . $code . ' id=' . $lang['id'] );
        return $lang['id'];
      }
    }
    $this->log->error('get_language_id_from_code: code=' . $code . ' id=Not Found');
    return FALSE;
  } // end function get_language_code_from_id()

  /**
   * get_language_name_from_code() - Gets a Language Name.
   * If the language is not found, inserts the language into the database.
   * @param  string $code     The Language Code
   * @param  string $default  (optional) The default language name to use & insert, if none found
   * @return string           The Language Name, or FALSE
   */
  function get_language_name_from_code( string $code, string $default='' ) {
    if( !$default ) {
      $default = $code;
    }
    $langs = $this->get_languages();
    if( !$langs ) {
      $this->log->notice('get_language_name_from_code: no languages found.  Attempting insert: ');
      $lang_id = $this->insert_language($code, $default);
      if( !$lang_id ) {
        $this->log->error('get_language_name_from_code: Can not insert language.');
        return FALSE;
      }
      return $default;
    }
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        $this->log->debug('get_language_name_from_code: code='
          . htmlentities($code) . ' name=' . htmlentities($lang['name']));
        return $lang['name'];
      }
    }
    $this->log->notice('get_language_name_from_code: Not Found.  Attempting insert:');
    $lang_id = $this->insert_language($code, $default);
    if( !$lang_id ) {
      $this->log->error('get_language_name_from_code: Can not insert language.');
      return FALSE;
    }
    return $default;
  }

  /**
   * get_dictionary_list()
   * @param  string $rel_url (opional) Relative URL of page
   * @return array           List of dictionaries
   */
  function get_dictionary_list( string $rel_url='' ) {
    $sql = 'SELECT DISTINCT sl, tl FROM word2word';
    $r = $this->db->query($sql);
    $langs = $this->get_languages();
    $dlist = array();
    foreach( $r as $d ) {
      $this->log->debug('d=',$d); $this->log->debug('langs=',$langs);
      $sl = $this->get_language_code_from_id($d['sl']); // Source Language Name
      $tl = $this->get_language_code_from_id($d['tl']); // Target Language Name
      $url = $rel_url . $sl . '/' . $tl . '/';
      $dlist[$url] = $langs[$sl]['name'] . ' to ' . $langs[$tl]['name'];
      $r_url = $rel_url . $tl . '/' . $sl . '/';
      if( !array_key_exists($r_url,$dlist) ) {
        $dlist[$r_url] = $langs[$tl]['name'] . ' to ' . $langs[$sl]['name'];
      }
    }
    asort($dlist);
    $this->log->debug('get_dictionary_list: got ' . sizeof($dlist) . ' dictionaries', array_keys($dlist));
    return $dlist;
  } // end function get_dictionary_list()

  /**
   * insert_word()
   * @param  string $word  The Word
   * @param  int           The ID of the inserted word, or FALSE
   */
  function insert_word( string $word ) {
    $sql = 'INSERT INTO word (word) VALUES (:word)';
    $bind=array('word'=>$word);
    $r = $this->db->queryb($sql, $bind);
    if( !$r ) {
      $this->log->error('insert_word: can not insert. word=' . htmlentities($word));
      return FALSE;
    }
    $id = $this->db->db->lastInsertId();
    $this->log->debug('inser_word: inserted id=' . $id . ' word=' . htmlentities($word));
    return $id;
  }

  /**
   * get_id_from_word()
   * Looks up the ID of a word.  If not found, then inserts the word
   * @param  string $word  The Word
   * @return int           The Word ID, or FALSE
   */
  function get_id_from_word( string $word ) {
    $sql = 'SELECT id FROM word WHERE word = :word LIMIT 1';
    $bind=array('word'=>$word);
    $r = $this->db->query($sql, $bind);
    if( !$r || !isset($r[0]) || !isset($r[0]['id']) ) {
      $this->log->notice('get_id_from_word: word not found: Inserting: ' . htmlentities($word));
      return $this->insert_word($word);
    }
    $this->log->debug('get_id_from_word: id=' . $r[0]['id'] . ' word=' . htmlentities($word));
    return $r[0]['id'];
  }

  /**
   * get_all_words()
   * @return array
   */
  function get_all_words() {
    $sql = 'SELECT word FROM word ORDER BY word COLLATE NOCASE';
    return $this->db->query($sql);
  }

  /**
   * insert_word2word()
   * @param  int $sw   Source Word ID
   * @param  int $sl   Source Language ID
   * @param  int $tw   Target Word ID
   * @param  int $tl   Target Language ID
   * @param  int       Inserted record ID, or FALSE
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
   * @param  int $sw   Source Word ID
   * @param  int $sl   Source Language ID
   * @param  int $tw   Target Word ID
   * @param  int $tl   Target Language ID
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
   * @param  int     $sl   (optional) Source Language ID
   * @param  int     $tl   (optional) Target Language ID
   * @return array         list of word pairs
   */
  function get_dictionary( int $sl=0, int $tl=0 ) {
    $this->log->debug("get_dictionary: sl=$sl tl=$tl");

    $select = '
    sw.word AS s_word, tw.word AS t_word,
    sl.code AS sc,     tl.code AS tc,
    sl.name AS sn,     tl.name AS tn';

    $order =   'ORDER BY sw.word COLLATE NOCASE, tw.word COLLATE NOCASE';

    $this->log->debug("get_dictionary: normalized: sl=$sl tl=$tl");

    $lang = '';
    $bind = array();

    if( $sl && $tl ) {
      $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
      $bind['sl'] = $sl;
      $bind['tl'] = $tl;
    } elseif( $sl && !$tl ) {
      $lang = 'AND ww.sl=:sl';
      $bind['sl'] = $sl;
    } elseif( !$sl && $tl ) {
      $lang = 'AND ww.tl=:tl';
      $bind['tl'] = $tl;
    }

    $sql = "
    SELECT $select
    FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
    WHERE sw.id = ww.sw AND tw.id = ww.tw
    AND   sl.id = ww.sl AND tl.id = ww.tl
    $lang
    $order";

    $r = $this->db->query($sql,$bind);

    return $r;

  } // end function get_dictionary()

  /**
   * search_dictionary()
   * @param  string  $sw   The Word to search thereupon
   * @param  int     $sl   (optional) Source Language ID
   * @param  int     $tl   (optional) Target Language ID
   * @return array         list of word pairs
   */
  function search_dictionary( string $word, int $sl=0, int $tl=0 ) {
      $this->log->debug("get_dictionary: sl=$sl tl=$tl word=" . htmlentities($word));

      $select = '
      sw.word AS s_word, tw.word AS t_word,
      sl.code AS sc,     tl.code AS tc,
      sl.name AS sn,     tl.name AS tn';
      $order = 'ORDER BY sw.word COLLATE NOCASE, tw.word COLLATE NOCASE';

      $this->log->debug("get_dictionary: sl=$sl tl=$tl word=" . htmlentities($word));

      if( $sl && $tl ) {
        $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
        $bind['sl'] = $sl;
        $bind['tl'] = $tl;
      } elseif( $sl && !$tl ) {
        $lang = 'AND ww.sl = :sl';
        $bind['sl'] = $sl;
      } elseif( !$sl && $tl ) {
        $lang = 'AND ww.tl = :tl';
        $bind['tl'] = $tl;
      } else {
        $lang = '';
      }

      $sql = "
      SELECT $select
      FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
      WHERE sw.id = ww.sw AND tw.id = ww.tw
      AND   sl.id = ww.sl AND tl.id = ww.tl
      $lang
      AND sw.word = :sw
      $order
      ";

      $bind['sw'] = $word;
      $r = $this->db->query($sql,$bind);
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
   * @param string $q Search String
   */
  function search( string $q ) {
    $r = '';
    $r .= '<p>search: ' . htmlentities($q) . '</p>';

    $s = $this->search_dictionary( $q, $sl=0, $tl=0 );
    $r .= '<pre>' . print_r($s,1) . '</pre>';

    return $r;
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

    $sn = $this->get_language_name_from_code($s, $default=$sn); // The Source Language Name
    if( !$sn ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $si = $this->get_language_id_from_code($s); // The Source Language ID
    if( !$si ) {
      $error = 'Error: can not get source language ID';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $tn = $this->get_language_name_from_code($t, $default=$tn); // The Target Language Name
    if( !$tn ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $ti = $this->get_language_id_from_code($t); // The Target Language ID
    if( !$si ) {
      $error = 'Error: can not get target language ID';
      print $error;
      $this->log->error("do_import: $error");
      return;
    }

    $this->log->debug("do import: sn=$sn si=$si tn=$tn ti=$ti");

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

      $swi = $this->get_id_from_word($sw); // The Source Word ID
      if( !$swi ) {
        print '<p>Error: Line #' . $line_count . ': Can Not Get/Insert Source Word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $tw = trim($wp[1]); // The Target Word
      if( !$tw ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing target word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $twi = $this->get_id_from_word($tw); // The Target Word ID
      if( !$twi ) {
        print '<p>Error: Line #' . $line_count . ': Can Not Get/Insert Target Word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $this->log->debug("do_import: sw=$sw swi=$swi si=$si tw=$tw twi=$twi ti=$ti");

      $bind = array( 'sw'=>$si, 'sl'=>$si, 'tw'=>$ti, 'tl'=>$ti );

      $r = $this->insert_word2word( $swi, $si, $twi, $ti );
      if( !$r ) {
        if( $this->db->db->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error';
        $error_count++; $skip_count++;
      } else {
        $import_count++;
      }

      // insert reverse pair
      $r = $this->insert_word2word( $twi, $ti, $swi, $si );
      if( !$r ) {
        if( $this->db->db->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error';
        $error_count++; $skip_count++;
      } else {
        $import_count++;
      }

      if( $line_count % 100 == 0 ) {
        print ' ' . $line_count . ' ';
        @ob_flush(); flush();
      } elseif( $line_count % 10 == 0 ) {
        print '.';
        @ob_flush(); flush();
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
