<?php // The Open Translation Engine (OTE) - Attogram Framework Module

namespace Attogram;

define('OTE_VERSION', '1.0.0-dev.13');

/**
 * Open Translation Engine (OTE) class
 */
class ote
{

  public $db;
  public $log;
  public $languages;
  public $dictionary_list;

  /**
   * __construct()
   * @param  object $db   Attogram PDO database object
   * @param  object $log  PSR-3 compliant logger
   * @return void
   */
  function __construct( $db, $log )
  {
    $this->db = $db;
    $this->log = $log;
    $this->log->debug('START OTE v' . OTE_VERSION);
  }

  /**
   * insert_language()
   * @param  string $code   The Language Code
   * @param  string $name   The Language Name
   * @return int            ID of the new language, or false
   */
  function insert_language( $code, $name )
  {
    $sql = 'INSERT INTO language (code, name) VALUES (:code, :name)';
    $bind=array( 'code' => $code, 'name' => $name );
    $r = $this->db->queryb( $sql, $bind );
    if( !$r ) {
      $this->log->error('insert_language: can not insert language');
      return false;
    }
    $id = $this->db->db->lastInsertId();
    $this->log->debug('insert_language: inserted id=' . $id
      . ' code=' . htmlentities($code) . ' name=' . htmlentities($name));
    unset($this->languages); // reset the language list
    return $id;
  }

  /**
   * get_languages() - get a list of all languages
   * @param  string $orderby  (optional) Column to sort on: id, code, or name
   * @return array
   */
  function get_languages( $orderby = 'id' )
  {
    if( isset($this->languages) && is_array($this->languages) ) {
      return $this->languages;
    }
    $this->languages = array();
    $sql = 'SELECT id, code, name FROM language ORDER by ' . $orderby;
    $r = $this->db->query($sql);
    if( !$r ) {
      $this->log->error('get_languages: Languages Not Found, or Query Failed');
      return $this->languages;
    }
    foreach( $r as $g ) {
      $this->languages[ $g['code'] ] = array( 'id'=>$g['id'], 'name'=>$g['name'] );
    }
    $this->log->debug('get_languages: got ' . sizeof($this->languages) . ' languages', $this->languages);
    return $this->languages;
  } // end function get_languages()

  /**
   * get_languages_count()
   */
  function get_languages_count()
  {
    return sizeof($this->get_languages());
  }

  /**
   * get_language_code_from_id()
   * @param  int     $id  The Language ID
   * @return string       The Language Code, or false
   */
  function get_language_code_from_id( $id )
  {
    $langs = $this->get_languages();
    foreach( $langs as $code => $lang ) {
      if( $lang['id'] == $id ) {
        //$this->log->debug('get_language_code_from_id: id=' . $id . ' code=' . $code );
        return $code;
      }
    }
    $this->log->error('get_language_code_from_id: id=' . $id . ' Not Found');
    return false;
  } // end function get_language_code_from_id()

  /**
   * get_language_id_from_code()
   * @param string  $code  The Language Code
   * @param int            The Language ID, or false
   */
  function get_language_id_from_code( $code )
  {
    $langs = $this->get_languages();
    if( !$langs ) {
      $this->log->error('get_language_id_from_code: no languages found');
      return false;
    }
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        //$this->log->debug('get_language_id_from_code: code=' . $code . ' id=' . $lang['id'] );
        return $lang['id'];
      }
    }
    $this->log->error('get_language_id_from_code: code=' . $code . ' id=Not Found');
    return false;
  } // end function get_language_code_from_id()

  /**
   * Gets a Language Name.
   * If the language is not found, inserts the language into the database.
   * @param  string $code The Language Code
   * @param  string $default_name  (optional) The default language name to use & insert, if none found
   * @return string The Language Name, or The Language Code on error
   */
  function get_language_name_from_code( $code, $default_name='' )
  {
    if( !$default_name ) {
      $default_name = $code;
    }
    $langs = $this->get_languages();
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        $this->log->debug('get_language_name_from_code: code='
          . htmlentities($code) . ' name=' . htmlentities($lang['name']));
        return $lang['name'];
      }
    }
    $this->log->notice('get_language_name_from_code: Not Found: ' . $code);
    if( $code ) {
      $this->log->notice('get_language_name_from_code: insert new language: code='
        . htmlentities($code) . ' name=' . $default_name);
      if( !$this->insert_language($code, $default_name) ) {
        $this->log->error('get_language_name_from_code: Can not insert language.');
      }
    }
    return $default_name;
  } // end function get_language_name_from_code()

  /**
    * get_languages_pulldown()
    * @param  string $name      (optional) Name of the select element
    * @param  string $selected  (optional) Name of option to mark as selected
    * @return string            HTML pulldown selector with all  listed
    */
  function get_languages_pulldown( $name = 'language',  $selected = '' )
  {
    //$this->log->debug("get_languages_pulldown: name=$name selected=$selected");
    $r = '<select class="form-control" name="' . $name . '">';
    $r .= '<option value="">All Languages</option>';
    $langs = $this->get_languages( $orderby='name' );
    foreach( $langs as $lang_code => $lang ) {
      if( $lang_code == $selected ) {
        $select = ' selected';
      } else {
        $select = '';
      }
      $r .= '<option value="' . $lang_code . '"' . $select . '>' . $lang['name']  . '</option>';
    }
    $r .= '</select>';
    return $r;
  } // end get_languages_pulldown()

  /**
   * get_dictionary_list()
   * @param  string  $lcode   (optional) Limit search to specific Language Code
   * @return array           List of dictionaries
   */
  function get_dictionary_list( $lcode = '' )
  {
    $this->log->debug("get_dictionary_list: lcode=$lcode");
    if( isset($this->dictionary_list)
     && is_array($this->dictionary_list)
     && isset($this->dictionary_list[$lcode])
    ) {
      return $this->dictionary_list[$lcode];
    }
    $sql = 'SELECT DISTINCT sl, tl FROM word2word';
    $bind = array();
    if( $lcode ) {
      $sql .= ' WHERE ( sl = :sl ) OR ( tl = :sl )';
      $bind['sl'] = $this->get_language_id_from_code($lcode);
    }
    $r = $this->db->query($sql,$bind);
    $langs = $this->get_languages();
    $dlist = array();
    foreach( $r as $d ) {
      $sl = $this->get_language_code_from_id($d['sl']); // Source Language Name
      $tl = $this->get_language_code_from_id($d['tl']); // Target Language Name
      $url = $sl . '/' . $tl . '/';
      $dlist[$url] = $langs[$sl]['name'] . ' to ' . $langs[$tl]['name'];
      $r_url = $tl . '/' . $sl . '/';
      if( !array_key_exists($r_url,$dlist) ) {
        $dlist[$r_url] = $langs[$tl]['name'] . ' to ' . $langs[$sl]['name'];
      }
    }
    asort($dlist);
    $this->log->debug('get_dictionary_list: got ' . sizeof($dlist) . ' dictionaries', $dlist);
    return $this->dictionary_list[$lcode] = $dlist;
  } // end function get_dictionary_list()

  /**
   * get_dictionary_count()
   * @param  string  $lcode   (optional) Limit search to specific Language Code
   * @return int              Number of dictionaries
   */
  function get_dictionary_count( $lcode = '' )
  {
    return sizeof( $this->get_dictionary_list( $lcode ) );
  }

  /**
   * insert_word()
   * @param  string $word  The Word
   * @param  int           The ID of the inserted word, or false
   */
  function insert_word( $word )
  {
    $sql = 'INSERT INTO word (word) VALUES (:word)';
    $bind = array('word'=>$word);
    $r = $this->db->queryb($sql, $bind);
    if( !$r ) {
      $this->log->error('insert_word: can not insert. word=' . htmlentities($word));
      return false;
    }
    $id = $this->db->db->lastInsertId();
    $this->log->debug('inser_word: inserted id=' . $id . ' word=' . htmlentities($word));
    return $id;
  }

  /**
   * get_id_from_word()
   * Looks up the ID of a word.  If not found, then inserts the word
   * @param  string $word  The Word
   * @return int           The Word ID, or false
   */
  function get_id_from_word( $word )
  {
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
   * @param int $limit (optional)
   * @param int $offset (optional)
   * @return array
   */
  function get_all_words( $limit = 0, $offset = 0 )
  {
    $sql = 'SELECT word FROM word ORDER BY word COLLATE NOCASE';
    if( $limit && $offset ) {
      $sql .= " LIMIT $limit OFFSET $offset";
    } elseif( $limit && !$offset ) {
      $sql .= " LIMIT $limit";
    } elseif( !$limit && $offset ) {
      $this->log->error('get_all_words: missing limit.  offset=' . $offset);
      return array();
    }
    return $this->db->query($sql);
  }

  /**
   * get_word_count()
   * @return int
   */
  function get_word_count()
  {
    $c = $this->db->query('SELECT count(id) AS count FROM word');
    return isset($c[0]['count']) ? $c[0]['count'] : '0';
  }

  /**
   * insert_word2word()
   * @param  int $sw   Source Word ID
   * @param  int $sl   Source Language ID
   * @param  int $tw   Target Word ID
   * @param  int $tl   Target Language ID
   * @param  int       Inserted record ID, or false
   */
  function insert_word2word( $sw, $sl, $tw, $tl )
  {
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
    return false;
  }

  /**
   * get_word2word()
   * @param  int $sw   Source Word ID
   * @param  int $sl   Source Language ID
   * @param  int $tw   Target Word ID
   * @param  int $tl   Target Language ID
   * @return boolean       true if word2word entry exists, else false
   */
  function get_word2word( $sw, $sl, $tw, $tl )
  {
    $bind = array('sw'=>$sw, 'sl'=>$sl, 'tw'=>$tw, 'tl'=>$tl);
    $this->log->debug('get_word2word', $bind);
    $sql = 'SELECT sw FROM word2word WHERE sw=:sw AND sl=:sl AND tw=:tw AND tl=:tl';
    $r = $this->db->query($sql,$bind);
    if( $r ) {
      $this->log->debug('get_word2word: exists');
      return true;
    } else {
      $this->log->debug('get_word2word: does not exist');
      return false;
    }
  }

  /**
   * get_dictionary()
   * @param  int     $sl   (optional) Source Language ID
   * @param  int     $tl   (optional) Target Language ID
   * @return array         list of word pairs
   */
  function get_dictionary( $sl = 0, $tl = 0 )
  {
    $this->log->debug("get_dictionary: sl=$sl tl=$tl");

    $select = '
    sw.word AS s_word, tw.word AS t_word,
    sl.code AS sc,     tl.code AS tc,
    sl.name AS sn,     tl.name AS tn';

    $order = 'ORDER BY
      sw.word COLLATE NOCASE,
      sl.name COLLATE NOCASE,
      tl.name COLLATE NOCASE,
      tw.word COLLATE NOCASE';

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

    $sql = "SELECT $select
    FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
    WHERE sw.id = ww.sw AND tw.id = ww.tw
    AND   sl.id = ww.sl AND tl.id = ww.tl $lang $order";

    $r = $this->db->query($sql,$bind);
    return $r;

  } // end function get_dictionary()

  /**
   * search_dictionary()
   * @param  string  $sw   The Word to search thereupon
   * @param  int     $sl   (optional) Source Language ID, defaults to none
   * @param  int     $tl   (optional) Target Language ID, defaults to none
   * @param  bool    $f    (optional) 💭 Fuzzy Search, defaults to false
   * @param  bool    $c    (optional) 🔠🔡 Case Sensitive Search, defaults to false
   * @return array         list of word pairs
   */
  function search_dictionary( $word, $sl = 0, $tl = 0, $f = false, $c = false )
  {

      $this->log->debug("search_dictionary: sl=$sl tl=$tl word=" . htmlentities($word));

      $select = '
      sw.word AS s_word, tw.word AS t_word,
      sl.code AS sc,     tl.code AS tc,
      sl.name AS sn,     tl.name AS tn';

      if( $c ) { // 🔠🔡 Case Sensitive Search
        $order_c = 'COLLATE NOCASE';
      } else {
        $order_c = '';
      }
      $order = "ORDER BY
        sw.word $order_c,
        sl.name $order_c,
        tl.name $order_c,
        tw.word $order_c";

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

      $sql = "SELECT $select
      FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
      WHERE sw.id = ww.sw AND tw.id = ww.tw
      AND   sl.id = ww.sl AND tl.id = ww.tl
      $lang";
      if( $f ) { // 💭 Fuzzy Search
        $qword = "AND sw.word LIKE '%' || :sw || '%' $order_c";
      } else {
        $qword = 'AND sw.word = :sw ' . $order_c;
      }

      $sql .= " $qword $order";

      $bind['sw'] = $word;
      $r = $this->db->query($sql,$bind);
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
  function do_import( $w, $d, $s, $t, $sn = '', $tn = '' )
  {

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

  /**
   * display_pair() - HTML display for a single translation word pair
   * @param  string  $sw   The Source Word
   * @param  string  $sc   The Source Language Code
   * @param  string  $tw   The Target Word
   * @param  string  $tc   The Target Language Code
   * @param  string  $path (optional) URL path, defaults to ''
   * @param  string  $d    (optional) The Deliminator, defaults to ' = '
   * @param  bool    $usc  (optional) Put Language Source Code in word URLS, defaults to true
   * @param  bool    $utc  (optional) Put Language Target Code in word URLs, defaults to false
   * @return string         HTML fragment
   */
  function display_pair( $sw, $sc, $tw, $tc, $path = '', $d = ' = ', $usc = true, $utc = false )
  {
    $s_url = $path . '/word/' . ($usc ? $sc : '') . '/' . ($utc ? $tc : '') . '/' . urlencode($sw);
    $t_url = $path . '/word/' . ($usc ? $tc : '') . '/' . ($utc ? $sc : '') . '/' . urlencode($tw);
    $sw = htmlentities($sw);
    $tw = htmlentities($tw);
    $sn = $this->get_language_name_from_code($sc);
    $tn = $this->get_language_name_from_code($tc);
    $r = '
<style>
a { color: inherit; }
</style>
<div class="row" style="font-size:18pt; border:1px solid #eeeeee; padding:2px;">
  <div class="col-xs-4 text-left">
    <a href="' . $s_url . '">' . $sw . '</a>
  </div>
  <div class="col-xs-1 text-center">
    ' . $d . '
  </div>
  <div class="col-xs-4 text-left">
    <a href="' . $t_url . '">' . $tw . '</a>
  </div>
  <div class="col-xs-3 text-left text-nowrap" style="font-size:9pt;">
    <small>' . "$sn $d $tn" . '</small>
  </div>
</div>
    ';
    return $r;
  } // end function display_pair

  /**
   * multiSort()
   * @params array ARG1
   * @params string ARG2, ARG3, ...
   */
  function DEPRECIATED_multiSort()
  {
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
