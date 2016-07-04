<?php // The Open Translation Engine (OTE) - ote class v0.5.0

namespace Attogram;

/**
 * Open Translation Engine (OTE) class
 */
class ote
{

  const OTE_VERSION = '1.1.1-dev';

  public $attogram;        // (object) The Attogram Framework object
  public $languages;       // (array) List of languages
  public $dictionary_list; // (array) List of dictionaries

  /**
   * initialize OTE
   * @param  object $attogram   The attogram framework object
   * @return void
   */
  public function __construct( $attogram )
  {
    $this->attogram = $attogram;
    $this->attogram->log->debug('START OTE v' . self::OTE_VERSION);
  }

  /**
   * Insert a language into the database
   * @param  string $code   The Language Code
   * @param  string $name   The Language Name
   * @return int            ID of the new language, or false
   */
  public function insert_language( $code, $name )
  {
    $sql = 'INSERT INTO language (code, name) VALUES (:code, :name)';
    $bind=array( 'code' => $code, 'name' => $name );
    $r = $this->attogram->db->queryb( $sql, $bind );
    if( !$r ) {
      $this->attogram->log->error('insert_language: can not insert language');
      return false;
    }
    $id = $this->attogram->db->db->lastInsertId();
    $this->attogram->log->debug('insert_language: inserted id=' . $id . ' code=' . $this->web_display($code) . ' name=' . $this->web_display($name));
    $this->attogram->event->info('ADD language: <code>' . $this->web_display($code) . '</code> ' . $this->web_display($name) );
    unset($this->languages); // reset the language list
    unset($this->dictionary_list); // reset the dictionary list
    return $id;
  }

  /**
   * Get a list of all languages
   * @param  string $orderby  (optional) Column to sort on: id, code, or name
   * @return array
   */
  public function get_languages( $orderby = 'id' )
  {
    if( isset($this->languages) && is_array($this->languages) ) {
      return $this->languages;
    }
    $this->languages = array();
    $sql = 'SELECT id, code, name FROM language ORDER by ' . $orderby;
    $r = $this->attogram->db->query($sql);
    if( !$r ) {
      $this->attogram->log->error('get_languages: Languages Not Found, or Query Failed');
      return $this->languages;
    }
    foreach( $r as $g ) {
      $this->languages[ $g['code'] ] = array( 'id'=>$g['id'], 'name'=>$g['name'] );
    }
    $this->attogram->log->debug('get_languages: got ' . sizeof($this->languages) . ' languages', $this->languages);
    return $this->languages;
  } // end function get_languages()

  /**
   * Get the number of languages
   * @return int
   */
  public function get_languages_count()
  {
    return sizeof($this->get_languages());
  }

  /**
   * Get a Language Code from Language ID
   * @param  int     $id  The Language ID
   * @return string       The Language Code, or false
   */
  public function get_language_code_from_id( $id )
  {
    foreach( $this->get_languages() as $code => $lang ) {
      if( $lang['id'] == $id ) {
        return $code;
      }
    }
    return false;
  } // end function get_language_code_from_id()

  /**
   * Get a Language Name from Language ID
   * @param  int     $id  The Language ID
   * @return string       The Language Name, or false
   */
  public function get_language_name_from_id( $id )
  {
    foreach( $this->get_languages() as $code => $lang ) {
      if( $lang['id'] == $id ) {
        return $lang['name'];
      }
    }
    return false;
  } // end function get_language_name_from_id()

  /**
   * Get a Language ID from Language Code
   * @param string  $code  The Language Code
   * @param int            The Language ID, or false
   */
  public function get_language_id_from_code( $code )
  {
    foreach( $this->get_languages() as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        return $lang['id'];
      }
    }
    return false;
  } // end function get_language_code_from_id()

  /**
   * Get a Language Name.
   * Optionally, if the language is not found, inserts the language into the database.
   * @param  string $code           The Language Code
   * @param  string $default_name   (optional) The default language name to use & insert, if none found
   * @param  bool $insert           (optional) Insert language into database, if not found. Defaults to false
   * @return string                 The Language Name, or false on error
   */
  public function get_language_name_from_code( $code, $default_name = '', $insert = false )
  {
    foreach( $this->get_languages() as $lang_code => $lang ) {
      if( $lang_code == $code ) {
        return $lang['name'];
      }
    }
    $this->attogram->log->notice('get_language_name_from_code: Not Found: ' . $code);
    if( !$insert ) {
      return false;
    }
    if( $code ) {
      $this->attogram->log->notice('get_language_name_from_code: insert new language: code=' . $this->web_display($code) . ' name=' . $default_name);
      if( !$default_name ) {
        $default_name = $code;
      }
      if( !$this->insert_language( $code, $default_name ) ) {
        $this->attogram->log->error('get_language_name_from_code: Can not insert language.');
      }
    }
    return $default_name;
  } // end function get_language_name_from_code()

  /**
    * Get an HTML pulldown selector filled with all Languages
    * @param  string $name      (optional) Name of the select element
    * @param  string $selected  (optional) Name of option to mark as selected
    * @param  string $class     (optional) class for the <select> element, defaults to 'form-control'
    * @return string            HTML pulldown selector with all  listed
    */
  public function get_languages_pulldown( $name = 'language',  $selected = '', $class = 'form-control' )
  {
    //$this->attogram->log->debug("get_languages_pulldown: name=$name selected=$selected class=$class");
    $r = '<select class="' . $class . '" name="' . $name . '">';
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
   * Get a list of all Dictionaries
   * @param  string  $lcode   (optional) Limit search to specific Language Code
   * @return array           List of dictionaries
   */
  public function get_dictionary_list( $lcode = '' )
  {
    $this->attogram->log->debug("get_dictionary_list: lcode=$lcode");
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
    $r = $this->attogram->db->query($sql,$bind);
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
    $this->attogram->log->debug('get_dictionary_list: got ' . sizeof($dlist) . ' dictionaries', $dlist);
    return $this->dictionary_list[$lcode] = $dlist;
  } // end function get_dictionary_list()

  /**
   * Get the number of dictionaries
   * @param  string  $lcode   (optional) Limit search to specific Language Code
   * @return int              Number of dictionaries
   */
  public function get_dictionary_count( $lcode = '' )
  {
    return sizeof( $this->get_dictionary_list( $lcode ) );
  }

  /**
   * Insert a word into the database
   * @param  string $word  The Word
   * @param  int           The ID of the inserted word, or false
   */
  public function insert_word( $word )
  {
    $sql = 'INSERT INTO word (word) VALUES (:word)';
    $bind = array('word'=>$word);
    $r = $this->attogram->db->queryb($sql, $bind);
    if( !$r ) {
      $this->attogram->log->error('insert_word: can not insert. word=' . $this->web_display($word));
      return false;
    }
    $id = $this->attogram->db->db->lastInsertId();
    $this->attogram->log->debug('inser_word: inserted id=' . $id . ' word=' . $this->web_display($word));
    $this->attogram->event->info('ADD word: <a href="' . $this->attogram->path . '/word///' . urlencode($word) . '">' . $this->web_display($word) . '</a>');
    return $id;
  }

  /**
   * Get ID of a word - Looks up the ID of a word.  If not found, then inserts the word
   * @param  string $word  The Word
   * @return int           The Word ID, or false
   */
  public function get_id_from_word( $word )
  {
    $sql = 'SELECT id FROM word WHERE word = :word LIMIT 1';
    $bind=array('word'=>$word);
    $r = $this->attogram->db->query($sql, $bind);
    if( !$r || !isset($r[0]) || !isset($r[0]['id']) ) {
      $this->attogram->log->notice('get_id_from_word: word not found: Inserting: ' . $this->web_display($word));
      return $this->insert_word($word);
    }
    $this->attogram->log->debug('get_id_from_word: id=' . $r[0]['id'] . ' word=' . $this->web_display($word));
    return $r[0]['id'];
  }

  /**
   * Get All Words
   * @param int $limit (optional)
   * @param int $offset (optional)
   * @param int $sl (optional) The Source Language ID
   * @param int $tl (optional) The Target Language ID
   * @return array List of words
   */
  public function get_all_words( $limit = 0, $offset = 0, $sl = 0, $tl = 0 )
  {

    $bind = array();
    if( !$sl && !$tl ) {      // No Source Language, No Target Language
      $select = 'SELECT distinct word FROM word';
    } elseif( $sl && !$tl ) { // Yes Source Language, No Target Language
      $select = 'SELECT distinct word FROM word, word2word WHERE word2word.sl = :sl AND word2word.sw = word.id';
      $bind['sl'] = $sl;
    } elseif( !$sl && $tl ) { // No source Language, Yes Target Language
      $select = 'SELECT distinct word FROM word, word2word WHERE word2word.tl = :tl AND word2word.sw = word.id';
      $bind['tl'] = $tl;
    } else {                  // Yes Source Language, Yes Target Language
      $select = 'SELECT distinct word FROM word, word2word WHERE word2word.sl = :sl AND word2word.tl = :tl AND word2word.sw = word.id';
      $bind['sl'] = $sl;
      $bind['tl'] = $tl;
    }

    $order = 'ORDER BY word COLLATE NOCASE';
    if( $limit && $offset ) {
      $limit = "LIMIT $limit OFFSET $offset";
    } elseif( $limit && !$offset ) {
      $limit = "LIMIT $limit";
    } elseif( !$limit && $offset ) {
      $this->attogram->log->error('get_all_words: missing limit.  offset=' . $offset);
      return array();
    }

    $sql = "$select $order $limit";
    return $this->attogram->db->query( $sql, $bind );
  }

  /**
   * Get the number of words
   * @param int $sl (optional) The Source Language ID
   * @param int $tl (optional) The Target Language ID
   * @return int
   */
  public function get_word_count( $sl = 0, $tl = 0 )
  {
    $bind = array();
    if( !$sl && !$tl ) {      // No Source Language, No Target Language
      $sql = 'SELECT count(DISTINCT word.word) AS count FROM word';
    } elseif( $sl && !$tl ) { // Yes Source Language, No Target Language
      $sql = 'SELECT count(DISTINCT word.word) AS count FROM word, word2word WHERE word2word.sl = :sl AND word2word.sw = word.id';
      $bind['sl'] = $sl;
    } elseif( !$sl && $tl ) { // No source Language, Yes Target Language
      $sql = 'SELECT count(DISTINCT word.word) AS count FROM word, word2word WHERE word2word.tl = :tl AND word2word.sw = word.id';
      $bind['tl'] = $tl;
    } else {                  // Yes Source Language, Yes Target Language
      $sql = 'SELECT count(DISTINCT word.word) AS count FROM word, word2word WHERE word2word.sl = :sl AND word2word.tl = :tl AND word2word.sw = word.id';
      $bind['sl'] = $sl;
      $bind['tl'] = $tl;
    }
    $c = $this->attogram->db->query( $sql, $bind );
    return isset($c[0]['count']) ? $c[0]['count'] : '0';
  }

  /**
   * Insert a translation into the database
   * @param  int $sw   Source Word ID
   * @param  int $sl   Source Language ID
   * @param  int $tw   Target Word ID
   * @param  int $tl   Target Language ID
   * @param  int       Inserted record ID, or false
   */
  public function insert_word2word( $sw, $sl, $tw, $tl )
  {
    $bind = array('sw'=>$sw, 'sl'=>$sl, 'tw'=>$tw, 'tl'=>$tl);
    $this->attogram->log->debug('insert_word2word', $bind);
    $sql = 'INSERT INTO word2word ( sw, sl, tw, tl ) VALUES ( :sw, :sl, :tw, :tl )';
    $r = $this->attogram->db->queryb($sql, $bind);
    if( $r ) {
      $id = $this->attogram->db->db->lastInsertId();
      $this->attogram->log->debug("insert_word2word: inserted. id=$id");
      return $id;
    }
    if( $this->attogram->db->db->errorCode() == '0000' ) {
      $this->attogram->log->notice('insert_word2word: Insert failed: duplicate entry.');
    } else {
      $this->attogram->log->error('insert_word2word: can not insert. errorInfo: '
        . print_r($this->attogram->db->db->errorInfo(),1) );
    }
    return false;
  }

  /**
   * Get a translation from the database
   * @param  int $sw   Source Word ID
   * @param  int $sl   Source Language ID
   * @param  int $tw   Target Word ID
   * @param  int $tl   Target Language ID
   * @return boolean   true if word2word entry exists, else false
   */
  public function get_word2word( $sw, $sl, $tw, $tl )
  {
    $bind = array('sw'=>$sw, 'sl'=>$sl, 'tw'=>$tw, 'tl'=>$tl);
    $this->attogram->log->debug('get_word2word', $bind);
    $sql = 'SELECT sw FROM word2word WHERE sw=:sw AND sl=:sl AND tw=:tw AND tl=:tl';
    $r = $this->attogram->db->query($sql,$bind);
    if( $r ) {
      $this->attogram->log->debug('get_word2word: exists');
      return true;
    }
    $this->attogram->log->debug('get_word2word: does not exist');
    return false;
  }

  /**
   * Get all of a dictionary
   * @param  int    $sl     (optional) Source Language ID
   * @param  int    $tl     (optional) Target Language ID
   * @param  int    $limit  (optional)
   * @param  int    $offset (optional)
   * @return array          list of word pairs
   */
  public function get_dictionary( $sl = 0, $tl = 0, $limit = false, $offset = false )
  {
    $this->attogram->log->debug("get_dictionary: sl=$sl tl=$tl limit=$limit offset=$offset");
    $select = '
    sw.word AS s_word, tw.word AS t_word,
    sl.code AS sc,     tl.code AS tc,
    sl.name AS sn,     tl.name AS tn';
    $order = 'ORDER BY
      sw.word COLLATE NOCASE,
      sl.name COLLATE NOCASE,
      tl.name COLLATE NOCASE,
      tw.word COLLATE NOCASE';
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
    $limit_clause = '';
    if( $limit && $offset ) {
      $limit_clause = "LIMIT $limit OFFSET $offset";
    } elseif( $limit && !$offset ) {
      $limit_clause = "LIMIT $limit";
    } elseif( !$limit && $offset ) {
      $this->attogram->log->error('get_dictionary: missing limit.  offset=' . $offset);
      return array();
    }

    $sql = "SELECT $select
    FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
    WHERE sw.id = ww.sw AND tw.id = ww.tw
    AND   sl.id = ww.sl AND tl.id = ww.tl $lang $order $limit_clause";
    $r = $this->attogram->db->query($sql,$bind);
    return $r;
  } // end function get_dictionary()

  public function get_dictionary_translations_count( $sl = 0, $tl = 0 )
  {
    $this->attogram->log->debug("get_dictionary_translations_count: sl=$sl tl=$tl ");
    $select = '';
    $lang = '';
    $bind = array();
    if( $sl && $tl ) {
      $lang = 'WHERE sl = :sl AND tl = :tl';
      $bind['sl'] = $sl;
      $bind['tl'] = $tl;
    } elseif( $sl && !$tl ) {
      $lang = 'WHERE sl = :sl';
      $bind['sl'] = $sl;
    } elseif( !$sl && $tl ) {
      $lang = 'WHERE tl = :tl';
      $bind['tl'] = $tl;
    }
    $sql = "SELECT count( word2word.id ) AS count FROM word2word $lang";
    $r = $this->attogram->db->query( $sql, $bind );
    return isset($r[0]['count']) ? $r[0]['count'] : '0';
  } // end get_dictionary_translations_count()

  /**
   * Get count of results for a Search of the dictionaries
   * @param  string $word   The Word to search thereupon
   * @param  int    $sl     (optional) Source Language ID, defaults to 0
   * @param  int    $tl     (optional) Target Language ID, defaults to 0
   * @param  bool   $f      (optional) 💭 Fuzzy Search, defaults to false
   * @param  bool   $c      (optional) 🔠🔡 Case Sensitive Search, defaults to false
   * @return int            number of results
   */
  public function get_count_search_dictionary( $word, $sl = 0, $tl = 0, $f = false, $c = false )
  {
      $select = 'SELECT count(sw.word) AS count';

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

      if( $c ) { // 🔠🔡 Case Sensitive Search
        $order_c = 'COLLATE NOCASE';
      } else {
        $order_c = '';
      }

      if( $f ) { // 💭 Fuzzy Search
        $qword = "AND sw.word LIKE '%' || :sw || '%' $order_c";
      } else {
        $qword = 'AND sw.word = :sw ' . $order_c;
      }
      $bind['sw'] = $word;

      $sql = "$select
      FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
      WHERE sw.id = ww.sw AND tw.id = ww.tw
      AND   sl.id = ww.sl AND tl.id = ww.tl
      $lang $qword";

      $r = $this->attogram->db->query( $sql, $bind );
      if( !$r || !isset($r[0]['count']) ) {
        return 0;
      }

      return $r[0]['count'];

  } // end function get_count_search_dictionary()

  /**
   * Search dictionaries
   * @param  string $word   The Word to search thereupon
   * @param  int    $sl     (optional) Source Language ID, defaults to 0
   * @param  int    $tl     (optional) Target Language ID, defaults to 0
   * @param  bool   $f      (optional) 💭 Fuzzy Search, defaults to false
   * @param  bool   $c      (optional) 🔠🔡 Case Sensitive Search, defaults to false
   * @param  int    $limit  (optional) Limit # of results per page, defaults to 100
   * @param  int    $offset (optional) result # to start listing at, defaults to 0
   * @return array          list of word pairs
   */
  public function search_dictionary( $word, $sl = 0, $tl = 0, $f = false, $c = false, $limit = 100, $offset = 0 )
  {

      $this->attogram->log->debug('search_dictionary: word=' . $this->web_display($word) . " sl=$sl tl=$tl f=$f c=$c limit=$limit offset=$offset");

      $hin = $this->insert_history( $word, $sl, $tl );

      $select = 'SELECT sw.word AS s_word, tw.word AS t_word, sl.code AS sc, tl.code AS tc, sl.name AS sn, tl.name AS tn';

      $count_select = 'SELECT count(sw.word) AS count';

      if( $c ) { // 🔠🔡 Case Sensitive Search
        $order_c = 'COLLATE NOCASE';
      } else {
        $order_c = '';
      }
      $order = "ORDER BY sw.word $order_c, sl.name $order_c, tl.name $order_c, tw.word $order_c";

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

      if( $f ) { // 💭 Fuzzy Search
        $qword = "AND sw.word LIKE '%' || :sw || '%' $order_c";
      } else {
        $qword = 'AND sw.word = :sw ' . $order_c;
      }
      $bind['sw'] = $word;

      if( $limit ) {
        $sql_limit = " LIMIT $limit";
        if( $offset ) {
          $sql_limit .= " OFFSET $offset";
        }
      }

      $sql = "$select
      FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
      WHERE sw.id = ww.sw AND tw.id = ww.tw
      AND   sl.id = ww.sl AND tl.id = ww.tl
      $lang $qword $order $sql_limit";

      $r = $this->attogram->db->query( $sql, $bind );

      return $r;

  } // end function search_dictionary()

  /**
   * insert an search history entry into the database
   * @param  string  $word   The Word
   * @param  int     $sl   (optional) Source Language ID, defaults to 0
   * @param  int     $tl   (optional) Target Language ID, defaults to 0
   * @return bool
   */
  public function insert_history( $word, $sl = 0, $tl = 0 )
  {
    $now = gmdate('Y-m-d');
    if( !$sl || !is_int($sl) ) {
      $sl = 0;
    }
    if( !$tl || !is_int($tl) ) {
      $tl = 0;
    }
    $this->attogram->log->debug('insert_history: date: ' . $now . ' sl: ' . $sl . ' tl: ' . $tl . ' word: ' . $this->web_display($word) );
    $sql = 'SELECT id FROM history WHERE word = :word AND date = :date AND sl = :sl AND tl = :tl';
    $bind = array( 'word' => $word, 'sl' => $sl, 'tl' => $tl, 'date' => $now );
    $rid = $this->attogram->db->query( $sql, $bind );
    if( !$rid ) {
      // insert new history entry for this date
      $sql = 'INSERT INTO history (word, sl, tl, date, count) VALUES (:word, :sl, :tl, :date, 1 )';
      return $this->attogram->db->queryb( $sql, $bind );
    }
    // update count
    $sql = 'UPDATE history SET count = count + 1 WHERE id = :id';
    $bind = array( 'id' => $rid[0]['id'] );
    return $this->attogram->db->queryb( $sql, $bind );
  } // end function insert_history()

  /**
   * Import translations into the database
   * @param string $w   List of word pairs, 1 pair to a line, with \n at end of line
   * @param string $d   Deliminator
   * @param string $s   Source Language Code
   * @param string $t   Target Language Code
   * @param string $sn  (optional) Source Language Name
   * @param string $tn  (optional) Target Language Name
   */
  public function do_import( $w, $d, $s, $t, $sn = '', $tn = '' )
  {

    $this->attogram->log->debug("do_import: s=$s t=$t d=$d sn=$sn tn=$tn w strlen=" . strlen($w));

    $d = str_replace('\t', "\t", $d); // allow real tabs

    $sn = $this->get_language_name_from_code( $s, $default = $sn, $insert = true ); // The Source Language Name
    if( !$sn ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $si = $this->get_language_id_from_code($s); // The Source Language ID
    if( !$si ) {
      $error = 'Error: can not get source language ID';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $tn = $this->get_language_name_from_code( $t, $default = $tn, $insert = true ); // The Target Language Name
    if( !$tn ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $ti = $this->get_language_id_from_code($t); // The Target Language ID
    if( !$si ) {
      $error = 'Error: can not get target language ID';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $this->attogram->log->debug("do import: sn=$sn si=$si tn=$tn ti=$ti");

    $lines = explode("\n", $w);

    print '<div class="container">'
    . 'Source Language: ID: <code>' . $si . '</code>'
    . ' Code: <code>' . $this->web_display($s) . '</code>'
    . ' Name: <code>' . $this->web_display($sn) . '</code>'
    . '<br />Target Language:&nbsp; ID: <code>' . $ti  . '</code>'
    . ' Code: <code>' . $this->web_display($t) . '</code>'
    . ' Name: <code>' . $this->web_display($tn) . '</code>'
    . '<br />Deliminator: <code>' . $this->web_display($d) . '</code>'
    . '<br />Lines: <code>' . sizeof($lines) . '</code><hr /><small>'
    ;

    $line_count = $import_count = $error_count = $skip_count = $dupe_count = 0;

    foreach($lines as $line) {

      set_time_limit(240);

      $line_count++;

      $line = urldecode($line);
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
        print '<p>Error: Line #' . $line_count . ': Deliminator (' . $this->web_display($d) . ') Not Found. Skipping line.</p>';
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

      $this->attogram->log->debug("do_import: sw=$sw swi=$swi si=$si tw=$tw twi=$twi ti=$ti");

      $bind = array( 'sw'=>$si, 'sl'=>$si, 'tw'=>$ti, 'tl'=>$ti );

      $r = $this->insert_word2word( $swi, $si, $twi, $ti );
      if( !$r ) {
        if( $this->attogram->db->db->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error';
        $error_count++; $skip_count++;
      } else {
        $import_count++;
        $this->attogram->event->info( 'ADD translation: '
          . '<code>' . $s . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($s)
          . '//' . urlencode($sw) . '">' . $this->web_display($sw) . '</a>'
          . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($t) . '//' . urlencode($tw)
          . '">' . $this->web_display($tw) . '</a> <code>' . $t . '</code>'
        );
      }

      // insert reverse pair
      $r = $this->insert_word2word( $twi, $ti, $swi, $si );
      if( !$r ) {
        if( $this->attogram->db->db->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error';
        $error_count++; $skip_count++;
      } else {
        $import_count++;
        $this->attogram->event->info( 'ADD translation: '
          . '<code>' . $t . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($t)
          . '//' . urlencode($tw) . '">' . $this->web_display($tw) . '</a>'
          . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($s) . '//' . urlencode($sw)
          . '">' . $this->web_display($sw) . '</a> <code>' . $s . '</code>'
        );
      }

      if( $line_count % 100 == 0 ) {
        print ' ' . $line_count . ' ';
      } elseif( $line_count % 10 == 0 ) {
        print '.';
      }
      @ob_flush(); flush();

    } // end foreach line

    print '</small><hr />';
    print '<code>' . $import_count . '</code> translations imported.<br />';
    print '<code>' . $error_count . '</code> errors.<br />';
    print '<code>' . $dupe_count . '</code> duplicates/existing.<br />';
    print '<code>' . $skip_count . '</code> lines skipped.<br />';
    print '</div>';

  } // end do_import

  /**
   * get count of entries in slush pile
   * @return int
   */
  public function get_count_slush_pile()
  {
    $r = $this->attogram->db->query('SELECT count(id) AS count FROM slush_pile');
    if( !$r ) {
      return 0;
    }
    return $r[0]['count'];
  }

  /**
   * add new entry to the slush pile
   * @param array $items  List of name=value pairs
   * @return bool
   */
  public function add_to_slush_pile( array $items = array() )
  {
    if( !$items ) {
      return false;
    }
    $names = array_keys($items);
    $values = array_values($items);
    $sql = 'INSERT INTO slush_pile (date, ' . implode(', ', $names) . ')'
    . ' VALUES ( datetime("now"), :' . implode(', :', $names) . ')';
    if( $this->attogram->db->queryb( $sql, $items ) ) {
      //$this->attogram->event->info( 'ADD slush_pile', $items );
      return true;
    }
    return false;
  }

  /**
   * delete an entry from the slush pile
   * @param  int  $id  The slush_pile.id to delete
   * @return bool
   */
  public function delete_from_slush_pile( $id )
  {
    // does slush pile entry exist?
    if( !$this->attogram->db->query('SELECT id FROM slush_pile WHERE id = :id LIMIT 1', array( 'id' => $id ) ) ) {
      $this->attogram->log->error('delete_from_slush_pile: Not Found id=' . $this->web_display($id));
      $_SESSION['error'] = 'Slush Pile entry not found (ID: ' . $this->web_display($id) . ')';
      return false;
    }
    $sql = 'DELETE FROM slush_pile WHERE id = :id';
    if( $this->attogram->db->queryb( $sql, array( 'id' => $id )) ) {
      return true;
    }
    $this->attogram->log->error('delete_from_slush_pile: Delete failed for id=' . $this->web_display($id));
    $_SESSION['error'] = 'Unable to delete Slush Pile entry (ID: ' . $this->web_display($id) . ')';
    return false;
  }

  /**
   * accept an entry from the slush pile for entry into the dictionary
   * @param  int  $id  The slush_pile.id to accept
   * @return bool
   */
  public function accept_slush_pile_entry( $id )
  {
    // get slush_pile entry
    $sql = 'SELECT * FROM slush_pile WHERE id = :id LIMIT 1';
    $spe = $this->attogram->db->query( $sql, array( 'id' => $id ) );
    if( !$spe ) {
      $this->attogram->log->error('accept_slush_pile_entry: can not find id=' . $this->web_display($id) );
      $_SESSION['error'] = 'Can not find requested slush pile entry';
      return false;
    }

    $type = $spe[0]['type'];
    switch( $type ) {
      case 'add': // add word2word translation
        $sw = $this->get_id_from_word(          $spe[0]['source_word'] );          // Source Word ID
        $sl = $this->get_language_id_from_code( $spe[0]['source_language_code'] ); // Source Language ID
        $tw = $this->get_id_from_word(          $spe[0]['target_word'] );          // Target Word ID
        $tl = $this->get_language_id_from_code( $spe[0]['target_language_code'] ); // Target Language ID
        if( $this->get_word2word( $sw, $sl, $tw, $tl ) ) {
          $del = $this->delete_from_slush_pile( $id ); // dev todo - check results
          $this->attogram->log->error('accept_slush_pile_entry: Add translation: word2word entry already exists. Deleted slush_pile.id=' . $this->web_display($id));
          $_SESSION['error'] = 'Translation already exists!  Slush pile entry deleted (ID: ' . $this->web_display($id) . ')';
          return false;
        }
        if( $f_id = $this->insert_word2word( $sw, $sl, $tw, $tl ) ) {
          $this->attogram->event->info( 'ADD translation: '
            . '<code>' . $spe[0]['source_language_code'] . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['source_language_code'])
            . '//' . urlencode($spe[0]['source_word']) . '">' . $this->web_display($spe[0]['source_word']) . '</a>'
            . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['target_language_code']) . '//' . urlencode($spe[0]['target_word'])
            . '">' . $this->web_display($spe[0]['target_word']) . '</a> <code>' . $spe[0]['target_language_code'] . '</code>'
          );
          if( $r_id = $this->insert_word2word( $tw, $tl, $sw, $sl ) ) {
            $this->attogram->event->info( 'ADD translation: '
              . '<code>' . $spe[0]['target_language_code'] . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['target_language_code'])
              . '//' . urlencode($spe[0]['target_word']) . '">' . $this->web_display($spe[0]['target_word']) . '</a>'
              . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['source_language_code']) . '//' . urlencode($spe[0]['source_word'])
              . '">' . $this->web_display($spe[0]['source_word']) . '</a> <code>' . $spe[0]['source_language_code'] . '</code>'
            );
            $del = $this->delete_from_slush_pile( $id ); // dev todo - check results
            $_SESSION['result'] = 'Added new translation: '
            . ' <code>' . $this->web_display($spe[0]['source_language_code']) . '</code> '
            . '<a href="../word/' . urlencode($spe[0]['source_language_code']) . '/' . urlencode($spe[0]['target_language_code']) . '/' . urlencode($spe[0]['source_word']) . '">' . $this->web_display($spe[0]['source_word']) . '</a>'
            . ' = '
            . '<a href="../word/' . urlencode($spe[0]['target_language_code']) . '/' . urlencode($spe[0]['source_language_code']) . '/' . urlencode($spe[0]['target_word']) . '">' . $this->web_display($spe[0]['target_word']) . '</a>'
            . ' <code>' . $this->web_display($spe[0]['target_language_code']) . '</code>';
            return true;
          }
          $this->attogram->log->error('accept_slush_pile_entry: Can not insert reverse word2word entry');
          $_SESSION['error'] = 'Failed to insert new reverse translation';
          return false;
        }
        $this->attogram->log->error('accept_slush_pile_entry: Can not insert word2word entry');
        $_SESSION['error'] = 'Failed to insert new translation';
        return false;
        break;

      case 'delete': // DEV TODO -- delete word2word translation
      default: // unknown type
        $this->attogram->log->error('accept_slush_pile_entry: id=' . $this->web_display($id) . ' INVALID type=' . $this->web_display($type));
        $_SESSION['error'] = 'Invalid slush pile entry (ID: ' . $this->web_display($id) . ')';
        return false;
        break;
    } // end switch on type

    return false;

  } // end function accept_slush_pile_entry()

  /**
   * HTML display for a single translation word pair
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
  public function display_pair( $sw, $sc, $tw, $tc, $path = '', $d = ' = ', $usc = true, $utc = false )
  {
    $s_url = $path . '/word/' . ($usc ? $sc : '') . '/' . ($utc ? $tc : '') . '/' . urlencode($sw);
    $t_url = $path . '/word/' . ($usc ? $tc : '') . '/' . ($utc ? $sc : '') . '/' . urlencode($tw);
    $sw = $this->web_display($sw);
    $tw = $this->web_display($tw);
    $sn = $this->get_language_name_from_code($sc);
    $tn = $this->get_language_name_from_code($tc);
    $edit_uid = md5($sw . $sc . $tw . $tc);
    $r = '
    <div class="row" style="border:1px solid #ccc;margin:2px;">
      <div class="col-xs-6 col-sm-4 text-left">
        <a href="' . $s_url . '" class="pair">' . $sw . '</a>
      </div>
      <div class="col-xs-6 col-sm-4 text-left">
        ' . $d . ' <a href="' . $t_url . '" class="pair">' . $tw . '</a>
      </div>
      <div class="col-xs-8 col-sm-2 text-left">
       <code><small>' . $sn . ' ' . $d . ' ' . $tn . '</small></code>
      </div>
      <div class="col-xs-4 col-sm-2 text-center">
        <a name="editi' . $edit_uid . '" id="editi' . $edit_uid . '" href="javascript:void(0);"
          onclick="$(\'#edit' . $edit_uid . '\').show();$(\'#editi' . $edit_uid . '\').hide();">🔧</a>
        <div id="edit' . $edit_uid . '" name="edit" style="display:none;">

         <form name="tag' . $edit_uid . '" id="tag' . $edit_uid . '" method="POST" style="display:inline;">
           <input type="hidden" name="type" value="tag">
           <input type="hidden" name="tw" value="' . $tw . '">
           <input type="hidden" name="sl" value="' . $sc . '">
           <input type="hidden" name="tl" value="' . $tc . '">
           <button type="send">⛓</button>
         </form>

         <form name="del' . $edit_uid . '" id="del' . $edit_uid . '" method="POST" style="display:inline;">
           <input type="hidden" name="type" value="del">
           <input type="hidden" name="tw" value="' . $tw . '">
           <input type="hidden" name="sl" value="' . $sc . '">
           <input type="hidden" name="tl" value="' . $tc . '">
           <button type="send">❌</button>
         </form>

         </form>
        </div>
      </div>
    </div>';
    return $r;
  } // end function display_pair

  /**
   * clean a string for web display
   * @param string $string  The string to clean
   * @return string  The cleaned string, or false
   */
  public function web_display( $string )
  {
    if( !is_string($string) ) {
      return false;
    }
    return htmlentities( $string, ENT_COMPAT, 'UTF-8' );
  }

} // end class ote
