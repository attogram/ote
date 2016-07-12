<?php // The Open Translation Engine (OTE) - ote class v0.6.8

namespace Attogram;

/**
 * Open Translation Engine (OTE) class
 */
class ote
{

  const OTE_VERSION = '1.1.5';

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
    $result = $this->attogram->database->queryb( $sql, $bind );
    if( !$result ) {
      $this->attogram->log->error('insert_language: can not insert language');
      return false;
    }
    $insert_id = $this->attogram->database->database->lastInsertId();
    $this->attogram->log->debug('insert_language: inserted id=' . $insert_id . ' code=' . $this->webDisplay($code) . ' name=' . $this->webDisplay($name));
    $this->attogram->event->info('ADD language: <code>' . $this->webDisplay($code) . '</code> ' . $this->webDisplay($name) );
    unset($this->languages); // reset the language list
    unset($this->dictionary_list); // reset the dictionary list
    return $insert_id;
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
    $result = $this->attogram->database->query($sql);
    if( !$result ) {
      $this->attogram->log->error('get_languages: Languages Not Found, or Query Failed');
      return $this->languages;
    }
    foreach( $result as $g ) {
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
   * @param  int     $language_id  The Language ID
   * @return string                The Language Code, or false
   */
  public function get_language_code_from_id( $language_id )
  {
    foreach( $this->get_languages() as $code => $lang ) {
      if( $lang['id'] == $language_id ) {
        return $code;
      }
    }
    return false;
  } // end function get_language_code_from_id()

  /**
   * Get a Language Name from Language ID
   * @param  int     $language_id  The Language ID
   * @return string                The Language Name, or false
   */
  public function get_language_name_from_id( $language_id )
  {
    foreach( $this->get_languages() as $lang ) {
      if( $lang['id'] == $language_id ) {
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
   * @param  bool   $insert         (optional) Insert language into database, if not found. Defaults to false
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
      $this->attogram->log->notice('get_language_name_from_code: insert new language: code=' . $this->webDisplay($code) . ' name=' . $default_name);
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
    $result = '<select class="' . $class . '" name="' . $name . '">';
    $result .= '<option value="">All Languages</option>';
    $langs = $this->get_languages( 'name' );
    foreach( $langs as $lang_code => $lang ) {
      $select = '';
      if( $lang_code == $selected ) {
        $select = ' selected';
      }
      $result .= '<option value="' . $lang_code . '"' . $select . '>' . $lang['name']  . '</option>';
    }
    $result .= '</select>';
    return $result;
  } // end get_languages_pulldown()

  /**
   * Get a list of all Dictionaries
   * @param  string  $lcode  (optional) Limit search to specific Language Code
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
    $result = $this->attogram->database->query($sql,$bind);
    $langs = $this->get_languages();
    $dlist = array();
    foreach( $result as $dictionary ) {
      $source_language_code = $this->get_language_code_from_id($dictionary['sl']); // Source Language Code
      $target_language_code = $this->get_language_code_from_id($dictionary['tl']); // Target Language Code
      $url = $source_language_code . '/' . $target_language_code . '/';
      $dlist[$url] = $langs[$source_language_code]['name'] . ' to ' . $langs[$target_language_code]['name'];
      $result_url = $target_language_code . '/' . $source_language_code . '/';
      if( !array_key_exists( $result_url, $dlist ) ) {
        $dlist[$result_url] = $langs[$target_language_code]['name'] . ' to ' . $langs[$source_language_code]['name'];
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
    $result = $this->attogram->database->queryb($sql, $bind);
    if( !$result ) {
      $this->attogram->log->error('insert_word: can not insert. word=' . $this->webDisplay($word));
      return false;
    }
    $insert_id = $this->attogram->database->database->lastInsertId();
    $this->attogram->log->debug('inser_word: inserted id=' . $insert_id . ' word=' . $this->webDisplay($word));
    $this->attogram->event->info('ADD word: <a href="' . $this->attogram->path . '/word///' . urlencode($word) . '">' . $this->webDisplay($word) . '</a>');
    return $insert_id;
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
    $result = $this->attogram->database->query($sql, $bind);
    if( !$result || !isset($result[0]) || !isset($result[0]['id']) ) {
      $this->attogram->log->notice('get_id_from_word: word not found: Inserting: ' . $this->webDisplay($word));
      return $this->insert_word($word);
    }
    $this->attogram->log->debug('get_id_from_word: id=' . $result[0]['id'] . ' word=' . $this->webDisplay($word));
    return $result[0]['id'];
  }

  /**
   * Get All Words
   * @param int $limit (optional)
   * @param int $offset (optional)
   * @param int $source_language_id (optional) The Source Language ID
   * @param int $target_language_id (optional) The Target Language ID
   * @return array List of words
   */
  public function get_all_words( $limit = 0, $offset = 0, $source_language_id = 0, $target_language_id = 0 )
  {

    $bind = array();

    $select = 'SELECT distinct word FROM word'; // No Source Language, No Target Language

    if( $source_language_id && !$target_language_id ) { // Yes Source Language, No Target Language
      $select .= ', word2word WHERE word2word.sl = :sl AND word2word.sw = word.id';
      $bind['sl'] = $source_language_id;
    }
    if( !$source_language_id && $target_language_id ) { // No source Language, Yes Target Language
      $select .= ', word2word WHERE word2word.tl = :tl AND word2word.sw = word.id';
      $bind['tl'] = $target_language_id;
    }
    if( $source_language_id && $target_language_id ) { // Yes Source Language, Yes Target Language
      $select .= ', word2word WHERE word2word.sl = :sl AND word2word.tl = :tl AND word2word.sw = word.id';
      $bind['sl'] = $source_language_id;
      $bind['tl'] = $target_language_id;
    }

    $order = 'ORDER BY word COLLATE NOCASE';
    if( $limit && $offset ) {
      $limit = "LIMIT $limit OFFSET $offset";
    }
    if( $limit && !$offset ) {
      $limit = "LIMIT $limit";
    }
    if( !$limit && $offset ) {
      $this->attogram->log->error('get_all_words: missing limit.  offset=' . $offset);
      return array();
    }

    $sql = "$select $order $limit";
    return $this->attogram->database->query( $sql, $bind );
  }

  /**
   * Get the number of words
   * @param int $source_language_id (optional) The Source Language ID
   * @param int $target_language_id (optional) The Target Language ID
   * @return int
   */
  public function get_word_count( $source_language_id = 0, $target_language_id = 0 )
  {
    $bind = array();
    $sql = 'SELECT count(DISTINCT word.word) AS count FROM word'; // No Source Language, No Target Language
    if( $source_language_id && !$target_language_id ) { // Yes Source Language, No Target Language
      $sql .= ', word2word WHERE word2word.sl = :sl AND word2word.sw = word.id';
      $bind['sl'] = $source_language_id;
    }
    if( !$source_language_id && $target_language_id ) { // No source Language, Yes Target Language
      $sql .= ', word2word WHERE word2word.tl = :tl AND word2word.sw = word.id';
      $bind['tl'] = $target_language_id;
    }
    if( $source_language_id && $target_language_id ){ // Yes Source Language, Yes Target Language
      $sql .= ', word2word WHERE word2word.sl = :sl AND word2word.tl = :tl AND word2word.sw = word.id';
      $bind['sl'] = $source_language_id;
      $bind['tl'] = $target_language_id;
    }
    $result = $this->attogram->database->query( $sql, $bind );
    return isset($result[0]['count']) ? $result[0]['count'] : '0';
  }

  /**
   * Insert a translation into the database
   * @param  int $source_word_id   Source Word ID
   * @param  int $source_language_id   Source Language ID
   * @param  int $target_word_id   Target Word ID
   * @param  int $target_language_id   Target Language ID
   * @param  int       Inserted record ID, or false
   */
  public function insert_word2word( $source_word_id, $source_language_id, $target_word_id, $target_language_id )
  {
    $bind = array('sw'=>$source_word_id, 'sl'=>$source_language_id, 'tw'=>$target_word_id, 'tl'=>$target_language_id);
    $this->attogram->log->debug('insert_word2word', $bind);
    $sql = 'INSERT INTO word2word ( sw, sl, tw, tl ) VALUES ( :sw, :sl, :tw, :tl )';
    $result = $this->attogram->database->queryb($sql, $bind);
    if( $result ) {
      $insert_id = $this->attogram->database->database->lastInsertId();
      $this->attogram->log->debug('insert_word2word: inserted. id=' . $insert_id);
      return $insert_id;
    }
    if( $this->attogram->database->database->errorCode() == '0000' ) {
      $this->attogram->log->notice('insert_word2word: Insert failed: duplicate entry.');
      return false;
    }
    $this->attogram->log->error('insert_word2word: can not insert. errorInfo: '
      . print_r($this->attogram->database->database->errorInfo(),1) );
  }

  /**
   * Does a translation exist?
   * @param  int $source_word_id      Source Word ID
   * @param  int $source_language_id  Source Language ID
   * @param  int $target_word_id      Target Word ID
   * @param  int $target_language_id  Target Language ID
   * @return boolean                  true if word2word entry exists, else false
   */
  public function has_word2word( $source_word_id, $source_language_id, $target_word_id, $target_language_id )
  {
    $bind = array('sw'=>$source_word_id, 'sl'=>$source_language_id, 'tw'=>$target_word_id, 'tl'=>$target_language_id);
    $this->attogram->log->debug('has_word2word', $bind);
    $sql = 'SELECT sw FROM word2word WHERE sw=:sw AND sl=:sl AND tw=:tw AND tl=:tl';
    $result = $this->attogram->database->query($sql,$bind);
    if( $result ) {
      $this->attogram->log->debug('has_word2word: exists');
      return true;
    }
    $this->attogram->log->debug('has_word2word: does not exist');
    return false;
  }

  /**
   * Get all of a dictionary
   * @param  int    $source_language_id     (optional) Source Language ID
   * @param  int    $target_language_id     (optional) Target Language ID
   * @param  int    $limit  (optional)
   * @param  int    $offset (optional)
   * @return array          list of word pairs
   */
  public function get_dictionary( $source_language_id = 0, $target_language_id = 0, $limit = false, $offset = false )
  {
    $this->attogram->log->debug("get_dictionary: sl=$source_language_id tl=$target_language_id limit=$limit offset=$offset");
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
    if( $source_language_id && $target_language_id ) {
      $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
      $bind['sl'] = $source_language_id;
      $bind['tl'] = $target_language_id;
    } elseif( $source_language_id && !$target_language_id ) {
      $lang = 'AND ww.sl=:sl';
      $bind['sl'] = $source_language_id;
    } elseif( !$source_language_id && $target_language_id ) {
      $lang = 'AND ww.tl=:tl';
      $bind['tl'] = $target_language_id;
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
    $result = $this->attogram->database->query($sql,$bind);
    return $result;
  } // end function get_dictionary()

  public function get_dictionary_translations_count( $source_language_id = 0, $target_language_id = 0 )
  {
    $this->attogram->log->debug("get_dictionary_translations_count: sl=$source_language_id tl=$target_language_id ");
    $lang = '';
    $bind = array();
    if( $source_language_id && $target_language_id ) {
      $lang = 'WHERE sl = :sl AND tl = :tl';
      $bind['sl'] = $source_language_id;
      $bind['tl'] = $target_language_id;
    }
    if( $source_language_id && !$target_language_id ) {
      $lang = 'WHERE sl = :sl';
      $bind['sl'] = $source_language_id;
    }
    if( !$source_language_id && $target_language_id ) {
      $lang = 'WHERE tl = :tl';
      $bind['tl'] = $target_language_id;
    }
    $sql = "SELECT count( word2word.id ) AS count FROM word2word $lang";
    $result = $this->attogram->database->query( $sql, $bind );
    return isset($result[0]['count']) ? $result[0]['count'] : '0';
  } // end get_dictionary_translations_count()

  /**
   * Get count of results for a Search of the dictionaries
   * @param  string $word   The Word to search thereupon
   * @param  int    $source_language_id  (optional) Source Language ID, defaults to 0
   * @param  int    $target_language_id  (optional) Target Language ID, defaults to 0
   * @param  bool   $fuzzy               (optional) 💭 Fuzzy Search, defaults to false
   * @param  bool   $case_sensitive      (optional) 🔠🔡 Case Sensitive Search, defaults to false
   * @return int                         number of results
   */
  public function get_count_search_dictionary( $word, $source_language_id = 0, $target_language_id = 0, $fuzzy = false, $case_sensitive = false )
  {
      $select = 'SELECT count(sw.word) AS count';
      $lang = '';
      if( $source_language_id && $target_language_id ) {
        $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
        $bind['sl'] = $source_language_id;
        $bind['tl'] = $target_language_id;
      }
      if( $source_language_id && !$target_language_id ) {
        $lang = 'AND ww.sl = :sl';
        $bind['sl'] = $source_language_id;
      }
      if( !$source_language_id && $target_language_id ) {
        $lang = 'AND ww.tl = :tl';
        $bind['tl'] = $target_language_id;
      }

      $order_c = '';
      if( $case_sensitive ) { // 🔠🔡 Case Sensitive Search
        $order_c = 'COLLATE NOCASE';
      }

      $qword = 'AND sw.word = :sw ' . $order_c;
      if( $fuzzy ) { // 💭 Fuzzy Search
        $qword = "AND sw.word LIKE '%' || :sw || '%' $order_c";
      }

      $bind['sw'] = $word;

      $sql = "$select
      FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
      WHERE sw.id = ww.sw AND tw.id = ww.tw
      AND   sl.id = ww.sl AND tl.id = ww.tl
      $lang $qword";

      $result = $this->attogram->database->query( $sql, $bind );
      if( !$result || !isset($result[0]['count']) ) {
        return 0;
      }

      return $result[0]['count'];

  } // end function get_count_search_dictionary()

  /**
   * Search dictionaries
   * @param  string $word   The Word to search thereupon
   * @param  int    $source_language_id  (optional) Source Language ID, defaults to 0
   * @param  int    $target_language_id  (optional) Target Language ID, defaults to 0
   * @param  bool   $fuzzy               (optional) 💭 Fuzzy Search, defaults to false
   * @param  bool   $case_sensitive      (optional) 🔠🔡 Case Sensitive Search, defaults to false
   * @param  int    $limit               (optional) Limit # of results per page, defaults to 100
   * @param  int    $offset              (optional) result # to start listing at, defaults to 0
   * @return array                       list of word pairs
   */
  public function search_dictionary( $word, $source_language_id = 0, $target_language_id = 0, $fuzzy = false, $case_sensitive = false, $limit = 100, $offset = 0 )
  {

      $this->attogram->log->debug('search_dictionary: word=' . $this->webDisplay($word) . " sl=$source_language_id tl=$target_language_id f=$fuzzy c=$case_sensitive limit=$limit offset=$offset");

      $this->insert_history( $word, $source_language_id, $target_language_id );

      $select = 'SELECT sw.word AS s_word, tw.word AS t_word, sl.code AS sc, tl.code AS tc, sl.name AS sn, tl.name AS tn';

      $order_c = '';
      if( $case_sensitive ) { // 🔠🔡 Case Sensitive Search
        $order_c = 'COLLATE NOCASE';
      }

      $order = "ORDER BY sw.word $order_c, sl.name $order_c, tl.name $order_c, tw.word $order_c";

      $lang = '';
      if( $source_language_id && $target_language_id ) {
        $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
        $bind['sl'] = $source_language_id;
        $bind['tl'] = $target_language_id;
      }
      if( $source_language_id && !$target_language_id ) {
        $lang = 'AND ww.sl = :sl';
        $bind['sl'] = $source_language_id;
      }
      if( !$source_language_id && $target_language_id ) {
        $lang = 'AND ww.tl = :tl';
        $bind['tl'] = $target_language_id;
      }

      $qword = 'AND sw.word = :sw ' . $order_c;
      if( $fuzzy ) { // 💭 Fuzzy Search
        $qword = "AND sw.word LIKE '%' || :sw || '%' $order_c";
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

      $result = $this->attogram->database->query( $sql, $bind );

      return $result;

  } // end function search_dictionary()

  /**
   * insert an search history entry into the database
   * @param  string  $word   The Word
   * @param  int     $source_language_id   (optional) Source Language ID, defaults to 0
   * @param  int     $target_language_id   (optional) Target Language ID, defaults to 0
   * @return bool
   */
  public function insert_history( $word, $source_language_id = 0, $target_language_id = 0 )
  {
    $now = gmdate('Y-m-d');
    if( !$source_language_id || !is_int($source_language_id) ) {
      $source_language_id = 0;
    }
    if( !$target_language_id || !is_int($target_language_id) ) {
      $target_language_id = 0;
    }
    $this->attogram->log->debug('insert_history: date: ' . $now . ' sl: ' . $source_language_id . ' tl: ' . $target_language_id . ' word: ' . $this->webDisplay($word) );
    $sql = 'SELECT id FROM history WHERE word = :word AND date = :date AND sl = :sl AND tl = :tl';
    $bind = array( 'word' => $word, 'sl' => $source_language_id, 'tl' => $target_language_id, 'date' => $now );
    $resultid = $this->attogram->database->query( $sql, $bind );
    if( !$resultid ) {
      // insert new history entry for this date
      $sql = 'INSERT INTO history (word, sl, tl, date, count) VALUES (:word, :sl, :tl, :date, 1 )';
      return $this->attogram->database->queryb( $sql, $bind );
    }
    // update count
    $sql = 'UPDATE history SET count = count + 1 WHERE id = :id';
    $bind = array( 'id' => $resultid[0]['id'] );
    return $this->attogram->database->queryb( $sql, $bind );
  } // end function insert_history()

  /**
   * Import translations into the database
   * @param string $translations   List of word pairs, 1 pair to a line, with \n at end of line
   * @param string $deliminator   Deliminator
   * @param string $source_language_code   Source Language Code
   * @param string $target_language_code  Target Language Code
   * @param string $source_language_name  (optional) Source Language Name
   * @param string $target_language_name  (optional) Target Language Name
   */
  public function do_import( $translations, $deliminator, $source_language_code, $target_language_code, $source_language_name = '', $target_language_name = '' )
  {

    $this->attogram->log->debug("do_import: s=$source_language_code t=$target_language_code d=$deliminator sn=$source_language_name tn=$target_language_name w strlen=" . strlen($translations));

    $deliminator = str_replace('\t', "\t", $deliminator); // allow real tabs

    $source_language_name = $this->get_language_name_from_code( $source_language_code, /*$default =*/ $source_language_name, /*$insert =*/ true ); // The Source Language Name
    if( !$source_language_name ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $source_language_id = $this->get_language_id_from_code($source_language_code); // The Source Language ID
    if( !$source_language_id ) {
      $error = 'Error: can not get source language ID';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $target_language_name = $this->get_language_name_from_code($target_language_code, /* $default =*/ $target_language_name, /* $insert =*/ true ); // The Target Language Name
    if( !$target_language_name ) {
      $error = 'Error: can not get source language name';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $target_language_id = $this->get_language_id_from_code($target_language_code); // The Target Language ID
    if( !$target_language_id ) {
      $error = 'Error: can not get target language ID';
      print $error;
      $this->attogram->log->error("do_import: $error");
      return;
    }

    $this->attogram->log->debug("do import: sn=$source_language_name si=$source_language_id tn=$target_language_name ti=$target_language_id");

    $lines = explode("\n", $translations);

    print '<div class="container">'
    . 'Source Language: ID: <code>' . $source_language_id . '</code>'
    . ' Code: <code>' . $this->webDisplay($source_language_code) . '</code>'
    . ' Name: <code>' . $this->webDisplay($source_language_name) . '</code>'
    . '<br />Target Language:&nbsp; ID: <code>' . $target_language_id  . '</code>'
    . ' Code: <code>' . $this->webDisplay($target_language_code) . '</code>'
    . ' Name: <code>' . $this->webDisplay($target_language_name) . '</code>'
    . '<br />Deliminator: <code>' . $this->webDisplay($deliminator) . '</code>'
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

      if( !preg_match('/' . $deliminator . '/', $line) ) {
        print '<p>Error: Line #' . $line_count . ': Deliminator (' . $this->webDisplay($deliminator) . ') Not Found. Skipping line.</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $translationsp = explode($deliminator, $line);
      if( sizeof($translationsp) != 2 ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Expecting 2 words, found ' . sizeof($translationsp) . ' words</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $source_word = trim($translationsp[0]); // The Source Word
      if( !$source_word ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing source word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $source_word_id = $this->get_id_from_word($source_word); // The Source Word ID
      if( !$source_word_id ) {
        print '<p>Error: Line #' . $line_count . ': Can Not Get/Insert Source Word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $target_word = trim($translationsp[1]); // The Target Word
      if( !$target_word ) {
        print '<p>Error: Line #' . $line_count . ': Malformed line.  Missing target word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $target_language_id = $this->get_id_from_word($target_word); // The Target Word ID
      if( !$target_language_id ) {
        print '<p>Error: Line #' . $line_count . ': Can Not Get/Insert Target Word</p>';
        $error_count++; $skip_count++;
        continue;
      }

      $this->attogram->log->debug("do_import: sw=$source_word swi=$source_word_id si=$source_language_id tw=$target_word twi=$target_language_id ti=$target_language_id");

      $result = $this->insert_word2word( $source_word_id, $source_language_id, $target_language_id, $target_language_id );
      if( !$result ) {
        if( $this->attogram->database->database->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error';
        $error_count++; $skip_count++;
      } else {
        $import_count++;
        $this->attogram->event->info( 'ADD translation: '
          . '<code>' . $source_language_code . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($source_language_code)
          . '//' . urlencode($source_word) . '">' . $this->webDisplay($source_word) . '</a>'
          . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($target_language_code) . '//' . urlencode($target_word)
          . '">' . $this->webDisplay($target_word) . '</a> <code>' . $target_language_code. '</code>'
        );
      }

      // insert reverse pair
      $result = $this->insert_word2word( $target_language_id, $target_language_id, $source_word_id, $source_language_id );
      if( !$result ) {
        if( $this->attogram->database->database->errorCode() == '0000' ) {
          //print '<p>Info: Line #' . $line_count . ': Duplicate.  Skipping line';
          $error_count++; $dupe_count++; $skip_count++;
          continue;
        }
        print '<p>Error: Line #' . $line_count . ': Database Insert Error';
        $error_count++; $skip_count++;
      } else {
        $import_count++;
        $this->attogram->event->info( 'ADD translation: '
          . '<code>' . $target_language_code. '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($target_language_code)
          . '//' . urlencode($target_word) . '">' . $this->webDisplay($target_word) . '</a>'
          . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($source_language_code) . '//' . urlencode($source_word)
          . '">' . $this->webDisplay($source_word) . '</a> <code>' . $source_language_code . '</code>'
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
    $result = $this->attogram->database->query('SELECT count(id) AS count FROM slush_pile');
    if( !$result ) {
      return 0;
    }
    return $result[0]['count'];
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
    $sql = 'INSERT INTO slush_pile (date, ' . implode(', ', $names) . ')'
    . ' VALUES ( datetime("now"), :' . implode(', :', $names) . ')';
    if( $this->attogram->database->queryb( $sql, $items ) ) {
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
  public function delete_from_slush_pile( $slush_id )
  {
    // does slush pile entry exist?
    if( !$this->attogram->database->query('SELECT id FROM slush_pile WHERE id = :id LIMIT 1', array( 'id' => $slush_id ) ) ) {
      $this->attogram->log->error('delete_from_slush_pile: Not Found id=' . $this->webDisplay($slush_id));
      $_SESSION['error'] = 'Slush Pile entry not found (ID: ' . $this->webDisplay($slush_id) . ')';
      return false;
    }
    $sql = 'DELETE FROM slush_pile WHERE id = :id';
    if( $this->attogram->database->queryb( $sql, array( 'id' => $slush_id  )) ) {
      return true;
    }
    $this->attogram->log->error('delete_from_slush_pile: Delete failed for id=' . $this->webDisplay($slush_id));
    $_SESSION['error'] = 'Unable to delete Slush Pile entry (ID: ' . $this->webDisplay($slush_id) . ')';
    return false;
  }

  /**
   * accept an entry from the slush pile for entry into the dictionary
   * @param  int  $id  The slush_pile.id to accept
   * @return bool
   */
  public function accept_slush_pile_entry( $slush_id )
  {
    // get slush_pile entry
    $sql = 'SELECT * FROM slush_pile WHERE id = :id LIMIT 1';
    $spe = $this->attogram->database->query( $sql, array( 'id' => $slush_id ) );
    if( !$spe ) {
      $this->attogram->log->error('accept_slush_pile_entry: can not find id=' . $this->webDisplay($slush_id) );
      $_SESSION['error'] = 'Can not find requested slush pile entry';
      return false;
    }

    $type = $spe[0]['type'];
    switch( $type ) {
      case 'add': // add word2word translation
        $source_word_id = $this->get_id_from_word( $spe[0]['source_word'] ); // Source Word ID
        $source_language_id = $this->get_language_id_from_code( $spe[0]['source_language_code'] ); // Source Language ID
        $target_word_id = $this->get_id_from_word( $spe[0]['target_word'] ); // Target Word ID
        $target_language_id = $this->get_language_id_from_code( $spe[0]['target_language_code'] ); // Target Language ID
        if( $this->has_word2word( $source_word_id, $source_language_id, $target_word_id, $target_language_id ) ) {
          $this->delete_from_slush_pile( $slush_id ); // dev todo - check results
          $this->attogram->log->error('accept_slush_pile_entry: Add translation: word2word entry already exists. Deleted slush_pile.id=' . $this->webDisplay($slush_id));
          $_SESSION['error'] = 'Translation already exists!  Slush pile entry deleted (ID: ' . $this->webDisplay($slush_id) . ')';
          return false;
        }
        if( $this->insert_word2word( $source_word_id, $source_language_id, $target_word_id, $target_language_id ) ) {
          $this->attogram->event->info( 'ADD translation: '
            . '<code>' . $spe[0]['source_language_code'] . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['source_language_code'])
            . '//' . urlencode($spe[0]['source_word']) . '">' . $this->webDisplay($spe[0]['source_word']) . '</a>'
            . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['target_language_code']) . '//' . urlencode($spe[0]['target_word'])
            . '">' . $this->webDisplay($spe[0]['target_word']) . '</a> <code>' . $spe[0]['target_language_code'] . '</code>'
          );
          if( $this->insert_word2word( $target_word_id, $target_language_id, $source_word_id, $source_language_id ) ) {
            $this->attogram->event->info( 'ADD translation: '
              . '<code>' . $spe[0]['target_language_code'] . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['target_language_code'])
              . '//' . urlencode($spe[0]['target_word']) . '">' . $this->webDisplay($spe[0]['target_word']) . '</a>'
              . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['source_language_code']) . '//' . urlencode($spe[0]['source_word'])
              . '">' . $this->webDisplay($spe[0]['source_word']) . '</a> <code>' . $spe[0]['source_language_code'] . '</code>'
            );
            $this->delete_from_slush_pile( $slush_id ); // dev todo - check results
            $_SESSION['result'] = 'Added new translation: '
            . ' <code>' . $this->webDisplay($spe[0]['source_language_code']) . '</code> '
            . '<a href="../word/' . urlencode($spe[0]['source_language_code']) . '/' . urlencode($spe[0]['target_language_code']) . '/' . urlencode($spe[0]['source_word']) . '">' . $this->webDisplay($spe[0]['source_word']) . '</a>'
            . ' = '
            . '<a href="../word/' . urlencode($spe[0]['target_language_code']) . '/' . urlencode($spe[0]['source_language_code']) . '/' . urlencode($spe[0]['target_word']) . '">' . $this->webDisplay($spe[0]['target_word']) . '</a>'
            . ' <code>' . $this->webDisplay($spe[0]['target_language_code']) . '</code>';
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
        $this->attogram->log->error('accept_slush_pile_entry: id=' . $this->webDisplay($slush_id) . ' INVALID type=' . $this->webDisplay($type));
        $_SESSION['error'] = 'Invalid slush pile entry (ID: ' . $this->webDisplay($slush_id) . ')';
        return false;
        break;
    } // end switch on type

    return false;

  } // end function accept_slush_pile_entry()

  /**
   * HTML display for a single translation word pair
   * @param  string  $source_word   The Source Word
   * @param  string  $source_language_code   The Source Language Code
   * @param  string  $target_word   The Target Word
   * @param  string  $target_language_code   The Target Language Code
   * @param  string  $path (optional) URL path, defaults to ''
   * @param  string  $deliminator    (optional) The Deliminator, defaults to ' = '
   * @param  bool    $usc  (optional) Put Language Source Code in word URLS, defaults to true
   * @param  bool    $utc  (optional) Put Language Target Code in word URLs, defaults to false
   * @return string         HTML fragment
   */
  public function display_pair( $source_word, $source_language_code, $target_word, $target_language_code, $path = '', $deliminator = ' = ', $usc = true, $utc = false )
  {
    $s_url = $path . '/word/' . ($usc ? $source_language_code : '') . '/' . ($utc ? $target_language_code : '') . '/' . urlencode($source_word);
    $t_url = $path . '/word/' . ($usc ? $target_language_code : '') . '/' . ($utc ? $source_language_code : '') . '/' . urlencode($target_word);
    $source_word = $this->webDisplay($source_word);
    $target_word = $this->webDisplay($target_word);
    $source_language_name = $this->get_language_name_from_code($source_language_code);
    $target_language_name = $this->get_language_name_from_code($target_language_code);
    $edit_uid = md5($source_word . $source_language_code . $target_word . $target_language_code);
    $result = '
    <div class="row" style="border:1px solid #ccc;margin:2px;">
      <div class="col-xs-6 col-sm-4 text-left">
        <a href="' . $s_url . '" class="pair">' . $source_word . '</a>
      </div>
      <div class="col-xs-6 col-sm-4 text-left">
        ' . $deliminator . ' <a href="' . $t_url . '" class="pair">' . $target_word . '</a>
      </div>
      <div class="col-xs-8 col-sm-2 text-left">
       <code><small>' . $source_language_name . ' ' . $deliminator . ' ' . $target_language_name . '</small></code>
      </div>
      <div class="col-xs-4 col-sm-2 text-center">
        <a name="editi' . $edit_uid . '" id="editi' . $edit_uid . '" href="javascript:void(0);"
          onclick="$(\'#edit' . $edit_uid . '\').show();$(\'#editi' . $edit_uid . '\').hide();">🔧</a>
        <div id="edit' . $edit_uid . '" name="edit" style="display:none;">

         <form name="tag' . $edit_uid . '" id="tag' . $edit_uid . '" method="POST" style="display:inline;">
           <input type="hidden" name="type" value="tag">
           <input type="hidden" name="tw" value="' . $target_word . '">
           <input type="hidden" name="sl" value="' . $source_language_code . '">
           <input type="hidden" name="tl" value="' . $target_language_code . '">
           <button type="send">⛓</button>
         </form>

         <form name="del' . $edit_uid . '" id="del' . $edit_uid . '" method="POST" style="display:inline;">
           <input type="hidden" name="type" value="del">
           <input type="hidden" name="tw" value="' . $target_word . '">
           <input type="hidden" name="sl" value="' . $source_language_code . '">
           <input type="hidden" name="tl" value="' . $target_language_code . '">
           <button type="send">❌</button>
         </form>

         </form>
        </div>
      </div>
    </div>';
    return $result;
  } // end function display_pair

  /**
   * clean a string for web display
   * @param string $string  The string to clean
   * @return string  The cleaned string, or false
   */
  public function webDisplay( $string )
  {
    if( !is_string($string) ) {
      return false;
    }
    return htmlentities( $string, ENT_COMPAT, 'UTF-8' );
  }

} // end class ote
