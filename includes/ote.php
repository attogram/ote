<?php
// The Open Translation Engine (OTE) - ote class v0.8.0

namespace Attogram;

/**
 * Open Translation Engine (OTE) class
 */
class OpenTranslationEngine
{

    const OTE_VERSION = '1.2.0';

    /** @var object $attogram  The Attogram Framework object */
    public $attogram;

    /** @var array $languages  List of languages */
    public $languages;

    /** @var array $dictionaryList  List of dictionaries */
    public $dictionaryList;

    /**
     * initialize OTE
     * @param  object $attogram  The attogram framework object
     * @return void
     */
    public function __construct($attogram)
    {
        $this->attogram = $attogram;
        $this->attogram->log->debug('START OTE v' . self::OTE_VERSION);
    }

    /**
     * Insert a language into the database
     * @param  string $code   The Language Code
     * @param  string $name   The Language Name
     * @return int            ID of the new language, or 0
     */
    public function insertLanguage($code, $name)
    {
        $result = $this->attogram->database->queryb(
            'INSERT INTO language (code, name) VALUES (:code, :name)',
            array('code' => $code, 'name' => $name)
        );
        if (!$result) {
            $this->attogram->log->error('insertLanguage: can not insert language');
            return 0;
        }
        $insertId = $this->attogram->database->database->lastInsertId();
        $this->attogram->log->debug('insertLanguage: inserted id=' . $insertId . ' code=' . $this->attogram->webDisplay($code) . ' name=' . $this->attogram->webDisplay($name));
        $this->attogram->event->info('ADD language: <code>' . $this->attogram->webDisplay($code) . '</code> ' . $this->attogram->webDisplay($name));
        unset($this->languages); // reset the language list
        unset($this->dictionaryList); // reset the dictionary list
        return $insertId;
    }

    /**
     * Get a list of all languages
     * @param  string $orderby  (optional) Column to sort on: id, code, or name
     * @return array
     */
    public function getLanguages($orderby = 'id')
    {
        if (isset($this->languages) && is_array($this->languages)) {
            return $this->languages;
        }
        $this->languages = array();
        $result = $this->attogram->database->query('SELECT id, code, name FROM language ORDER by ' . $orderby);
        if (!$result) {
            $this->attogram->log->error('getLanguages: Languages Not Found, or Query Failed');
            return $this->languages;
        }
        foreach ($result as $lang) {
            $this->languages[$lang['code']] = array('id' => $lang['id'], 'name' => $lang['name']);
        }
        $this->attogram->log->debug('getLanguages: got ' . sizeof($this->languages) . ' languages', $this->languages);
        return $this->languages;
    } // end function getLanguages()

    /**
     * Get the number of languages
     * @return int
     */
    public function getLanguagesCount()
    {
        return sizeof($this->getLanguages());
    }

    /**
     * Get a Language Code from Language ID
     * @param int $languageId  The Language ID
     * @return string  The Language Code, or empty string
     */
    public function getLanguageCodeFromId($languageId)
    {
        foreach ($this->getLanguages() as $code => $lang) {
            if ($lang['id'] == $languageId) {
                return $code;
            }
        }
        return '';
    } // end function getLanguageCodeFromId()

    /**
     * Get a Language Name from Language ID
     * @param int $languageId  The Language ID
     * @return string  The Language Name, or empty string
     */
    public function getLanguageNameFromId($languageId)
    {
        foreach ($this->getLanguages() as $lang) {
            if ($lang['id'] == $languageId) {
                return $lang['name'];
            }
        }
        return '';
    } // end function getLanguageNameFromId()

    /**
     * Get a Language ID from Language Code
     * @param string $code  The Language Code
     * @return int  The Language ID, or 0
     */
    public function getLanguageIdFromCode($code)
    {
        foreach ($this->getLanguages() as $langCode => $lang) {
            if ($langCode == $code) {
                return $lang['id'];
            }
        }
        return 0;
    } // end function getLanguageCodeFromId()

    /**
     * Get a Language Name.
     * Optionally, if the language is not found, inserts the language into the database.
     * @param  string $code         The Language Code
     * @param  string $defaultName  (optional) The default language name to use & insert, if none found
     * @param  bool   $insert       (optional) Insert language into database, if not found. Defaults to false
     * @return string               The Language Name, or empty string
     */
    public function getLanguageNameFromCode($code, $defaultName = '', $insert = false)
    {
        foreach ($this->getLanguages() as $langCode => $lang) {
            if ($langCode == $code) {
                return $lang['name'];
            }
        }
        $this->attogram->log->notice('getLanguageNameFromCode: Not Found: ' . $code);
        if (!$insert) {
            return '';
        }
        if ($code) {
            $this->attogram->log->notice('getLanguageNameFromCode: insert new language: code=' . $this->attogram->webDisplay($code) . ' name=' . $defaultName);
            if (!$defaultName) {
                $defaultName = $code;
            }
            if (!$this->insertLanguage($code, $defaultName)) {
                $this->attogram->log->error('getLanguageNameFromCode: Can not insert language.');
            }
        }
        return $defaultName;
    } // end function getLanguageNameFromCode()

    /**
      * Get an HTML pulldown selector filled with all Languages
      * @param  string $name      (optional) Name of the select element
      * @param  string $selected  (optional) Name of option to mark as selected
      * @param  string $class     (optional) class for the <select> element, defaults to 'form-control'
      * @return string            HTML pulldown selector with all listed languages
      */
    public function getLanguagesPulldown($name = 'language', $selected = '', $class = 'form-control')
    {
        $result = '<select class="' . $class . '" name="' . $name . '">';
        $result .= '<option value="">All Languages</option>';
        $langs = $this->getLanguages('name');
        foreach ($langs as $langCode => $lang) {
            $select = '';
            if ($langCode == $selected) {
                $select = ' selected';
            }
            $result .= '<option value="' . $langCode . '"' . $select . '>' . $lang['name']  . '</option>';
        }
        $result .= '</select>';
        return $result;
    } // end getLanguagesPulldown()

    /**
     * Get a list of all Dictionaries
     * @param  string $lcode  (optional) Limit search to specific Language Code
     * @return array          List of dictionaries
     */
    public function getDictionaryList($lcode = '')
    {
        $this->attogram->log->debug("getDictionaryList: lcode=$lcode");
        if (isset($this->dictionaryList) && is_array($this->dictionaryList) && isset($this->dictionaryList[$lcode])) {
            return $this->dictionaryList[$lcode];
        }
        $sql = 'SELECT DISTINCT sl, tl FROM word2word';
        $bind = array();
        if ($lcode) {
            $sql .= ' WHERE (sl = :sl) OR (tl = :sl)';
            $bind['sl'] = $this->getLanguageIdFromCode($lcode);
        }
        $result = $this->attogram->database->query($sql, $bind);
        $langs = $this->getLanguages();
        $dlist = array();
        foreach ($result as $dictionary) {
            $sourceLanguageCode = $this->getLanguageCodeFromId($dictionary['sl']); // Source Language Code
            $targetLanguageCode = $this->getLanguageCodeFromId($dictionary['tl']); // Target Language Code
            $url = $sourceLanguageCode . '/' . $targetLanguageCode . '/';
            $dlist[$url] = $langs[$sourceLanguageCode]['name'] . ' to ' . $langs[$targetLanguageCode]['name'];
            $resultUrl = $targetLanguageCode . '/' . $sourceLanguageCode . '/';
            if (!array_key_exists($resultUrl, $dlist)) {
                $dlist[$resultUrl] = $langs[$targetLanguageCode]['name'] . ' to ' . $langs[$sourceLanguageCode]['name'];
            }
        }
        asort($dlist);
        $this->attogram->log->debug('getDictionaryList: got ' . sizeof($dlist) . ' dictionaries', $dlist);
        return $this->dictionaryList[$lcode] = $dlist;
    } // end function getDictionaryList()

    /**
     * Get the number of dictionaries
     * @param  string  $lcode  (optional) Limit search to specific Language Code
     * @return int             Number of dictionaries
     */
    public function getDictionaryCount($lcode = '')
    {
        return sizeof($this->getDictionaryList($lcode));
    }

    /**
     * Insert a word into the database
     * @param  string $word  The Word
     * @param  int           The ID of the inserted word, or 0
     */
    public function insertWord($word)
    {
        $result = $this->attogram->database->queryb(
            'INSERT INTO word (word) VALUES (:word)',
            array('word' => $word)
        );
        if (!$result) {
            $this->attogram->log->error('insertWord: can not insert. word=' . $this->attogram->webDisplay($word));
            return 0;
        }
        $insertId = $this->attogram->database->database->lastInsertId();
        $this->attogram->log->debug('inser_word: inserted id=' . $insertId . ' word=' . $this->attogram->webDisplay($word));
        $this->attogram->event->info('ADD word: <a href="' . $this->attogram->path . '/word///' . urlencode($word) . '">' . $this->attogram->webDisplay($word) . '</a>');
        return $insertId;
    }

    /**
     * Get ID of a word - Looks up the ID of a word.  If not found, then inserts the word
     * @param string $word  The Word
     * @return int  The Word ID, or 0
     */
    public function getIdFromWord($word)
    {
        $result = $this->attogram->database->query(
            'SELECT id FROM word WHERE word = :word LIMIT 1',
            array('word '=> $word)
        );
        if (!$result || !isset($result[0]) || !isset($result[0]['id'])) {
            $this->attogram->log->notice('getIdFromWord: word not found: Inserting: ' . $this->attogram->webDisplay($word));
            return $this->insertWord($word);
        }
        $this->attogram->log->debug('getIdFromWord: id=' . $result[0]['id'] . ' word=' . $this->attogram->webDisplay($word));
        return $result[0]['id'];
    }

    /**
     * Get All Words
     * @param int $limit             (optional) SQL Limit
     * @param int $offset            (optional) SQL Offset
     * @param int $sourceLanguageId  (optional) The Source Language ID
     * @param int $targetLanguageId  (optional) The Target Language ID
     * @return array                 List of words
     */
    public function getAllWords($limit = 0, $offset = 0, $sourceLanguageId = 0, $targetLanguageId = 0)
    {
        $bind = array();
        $select = 'SELECT distinct word FROM word'; // No Source Language, No Target Language
        if ($sourceLanguageId && !$targetLanguageId) { // Yes Source Language, No Target Language
            $select .= ', word2word WHERE word2word.sl = :sl AND word2word.sw = word.id';
            $bind['sl'] = $sourceLanguageId;
        }
        if (!$sourceLanguageId && $targetLanguageId) { // No source Language, Yes Target Language
            $select .= ', word2word WHERE word2word.tl = :tl AND word2word.sw = word.id';
            $bind['tl'] = $targetLanguageId;
        }
        if ($sourceLanguageId && $targetLanguageId) { // Yes Source Language, Yes Target Language
            $select .= ', word2word WHERE word2word.sl = :sl AND word2word.tl = :tl AND word2word.sw = word.id';
            $bind['sl'] = $sourceLanguageId;
            $bind['tl'] = $targetLanguageId;
        }
        $order = 'ORDER BY word COLLATE NOCASE';
        if ($limit && $offset) {
            $limit = "LIMIT $limit OFFSET $offset";
        }
        if ($limit && !$offset) {
            $limit = "LIMIT $limit";
        }
        if (!$limit && $offset) {
            $this->attogram->log->error('getAllWords: missing limit.  offset=' . $offset);
            return array();
        }
        return $this->attogram->database->query("$select $order $limit", $bind);
    }

    /**
     * Get the number of words
     * @param int $sourceLanguageId  (optional) The Source Language ID
     * @param int $targetLanguageId  (optional) The Target Language ID
     * @return int
     */
    public function getWordCount($sourceLanguageId = 0, $targetLanguageId = 0)
    {
        $bind = array();
        $sql = 'SELECT count(DISTINCT word.word) AS count FROM word'; // No Source Language, No Target Language
        if ($sourceLanguageId && !$targetLanguageId) { // Yes Source Language, No Target Language
            $sql .= ', word2word WHERE word2word.sl = :sl AND word2word.sw = word.id';
            $bind['sl'] = $sourceLanguageId;
        }
        if (!$sourceLanguageId && $targetLanguageId) { // No source Language, Yes Target Language
            $sql .= ', word2word WHERE word2word.tl = :tl AND word2word.sw = word.id';
            $bind['tl'] = $targetLanguageId;
        }
        if ($sourceLanguageId && $targetLanguageId) { // Yes Source Language, Yes Target Language
            $sql .= ', word2word WHERE word2word.sl = :sl AND word2word.tl = :tl AND word2word.sw = word.id';
            $bind['sl'] = $sourceLanguageId;
            $bind['tl'] = $targetLanguageId;
        }
        $result = $this->attogram->database->query($sql, $bind);
        return isset($result[0]['count']) ? $result[0]['count'] : '0';
    }

    /**
     * Insert a translation into the database
     * @param  int $sourceWordId      Source Word ID
     * @param  int $sourceLanguageId  Source Language ID
     * @param  int $targetWordId      Target Word ID
     * @param  int $targetLanguageId  Target Language ID
     * @param int  Inserted record ID, or 0
     */
    public function insertWord2word($sourceWordId, $sourceLanguageId, $targetWordId, $targetLanguageId)
    {
        $bind = array('sw'=>$sourceWordId, 'sl'=>$sourceLanguageId, 'tw'=>$targetWordId, 'tl'=>$targetLanguageId);
        $this->attogram->log->debug('insertWord2word', $bind);
        $result = $this->attogram->database->queryb('INSERT INTO word2word (sw, sl, tw, tl) VALUES (:sw, :sl, :tw, :tl)', $bind);
        if ($result) {
            $insertId = $this->attogram->database->database->lastInsertId();
            $this->attogram->log->debug('insertWord2word: inserted. id=' . $insertId);
            return $insertId;
        }
        if ($this->attogram->database->database->errorCode() == '0000') {
            $this->attogram->log->notice('insertWord2word: Insert failed: duplicate entry.');
            return 0;
        }
        $this->attogram->log->error('insertWord2word: can not insert. errorInfo: '
            . print_r($this->attogram->database->database->errorInfo(), true));
    }

    /**
     * Does a translation exist?
     * @param  int $sourceWordId      Source Word ID
     * @param  int $sourceLanguageId  Source Language ID
     * @param  int $targetWordId      Target Word ID
     * @param  int $targetLanguageId  Target Language ID
     * @return boolean                true if word2word entry exists, else false
     */
    public function hasWord2Word($sourceWordId, $sourceLanguageId, $targetWordId, $targetLanguageId)
    {
        $bind = array('sw'=>$sourceWordId, 'sl'=>$sourceLanguageId, 'tw'=>$targetWordId, 'tl'=>$targetLanguageId);
        $this->attogram->log->debug('hasWord2Word', $bind);
        $result = $this->attogram->database->query('SELECT sw FROM word2word WHERE sw=:sw AND sl=:sl AND tw=:tw AND tl=:tl', $bind);
        if ($result) {
            $this->attogram->log->debug('hasWord2Word: exists');
            return true;
        }
        $this->attogram->log->debug('hasWord2Word: does not exist');
        return false;
    }

    /**
     * Get all of a dictionary
     * @param  int    $sourceLanguageId  (optional) Source Language ID
     * @param  int    $targetLanguageId  (optional) Target Language ID
     * @param  int    $limit             (optional) SQL LImit
     * @param  int    $offset            (optional) SQL Offset
     * @return array                     list of word pairs
     */
    public function getDictionary($sourceLanguageId = 0, $targetLanguageId = 0, $limit = false, $offset = false)
    {
        $this->attogram->log->debug("getDictionary: sl=$sourceLanguageId tl=$targetLanguageId limit=$limit offset=$offset");
        $select = 'sw.word AS s_word, tw.word AS t_word, sl.code AS sc, tl.code AS tc, sl.name AS sn, tl.name AS tn';
        $order = 'ORDER BY sw.word COLLATE NOCASE, sl.name COLLATE NOCASE, tl.name COLLATE NOCASE, tw.word COLLATE NOCASE';
        $lang = '';
        $bind = array();
        if ($sourceLanguageId && $targetLanguageId) {
            $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
            $bind['sl'] = $sourceLanguageId;
            $bind['tl'] = $targetLanguageId;
        } elseif ($sourceLanguageId && !$targetLanguageId) {
            $lang = 'AND ww.sl=:sl';
            $bind['sl'] = $sourceLanguageId;
        } elseif (!$sourceLanguageId && $targetLanguageId) {
            $lang = 'AND ww.tl=:tl';
            $bind['tl'] = $targetLanguageId;
        }
        $limitClause = '';
        if ($limit && $offset) {
            $limitClause = "LIMIT $limit OFFSET $offset";
        } elseif ($limit && !$offset) {
            $limitClause = "LIMIT $limit";
        } elseif (!$limit && $offset) {
            $this->attogram->log->error('getDictionary: missing limit.  offset=' . $offset);
            return array();
        }
        $sql = "SELECT $select
        FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
        WHERE sw.id = ww.sw AND tw.id = ww.tw
        AND   sl.id = ww.sl AND tl.id = ww.tl $lang $order $limitClause";
        $result = $this->attogram->database->query($sql, $bind);
        return $result;
    } // end function getDictionary()

    /**
     * @param int $sourceLanguageId
     * @param int $targetLanguageId
     * @return int
     */
    public function getDictionaryTranslationsCount($sourceLanguageId = 0, $targetLanguageId = 0)
    {
        $this->attogram->log->debug("getDictionaryTranslationsCount: sl=$sourceLanguageId tl=$targetLanguageId ");
        $lang = '';
        $bind = array();
        if ($sourceLanguageId && $targetLanguageId) {
            $lang = 'WHERE sl = :sl AND tl = :tl';
            $bind['sl'] = $sourceLanguageId;
            $bind['tl'] = $targetLanguageId;
        } elseif ($sourceLanguageId && !$targetLanguageId) {
            $lang = 'WHERE sl = :sl';
            $bind['sl'] = $sourceLanguageId;
        } elseif (!$sourceLanguageId && $targetLanguageId) {
            $lang = 'WHERE tl = :tl';
            $bind['tl'] = $targetLanguageId;
        }
        $result = $this->attogram->database->query("SELECT count(word2word.id) AS count FROM word2word $lang", $bind);
        return isset($result[0]['count']) ? $result[0]['count'] : '0';
    } // end getDictionaryTranslationsCount()

    /**
     * Get count of results for a Search of the dictionaries
     * @param  string $word   The Word to search thereupon
     * @param  int    $sourceLanguageId  (optional) Source Language ID, defaults to 0
     * @param  int    $targetLanguageId  (optional) Target Language ID, defaults to 0
     * @param  bool   $fuzzy             (optional) 💭 Fuzzy Search, defaults to false
     * @param  bool   $caseSensitive     (optional) 🔠🔡 Case Sensitive Search, defaults to false
     * @return int                       number of results
     */
    public function getCountSearchDictionary($word, $sourceLanguageId = 0, $targetLanguageId = 0, $fuzzy = false, $caseSensitive = false)
    {
        $select = 'SELECT count(sw.word) AS count';
        $lang = '';
        if ($sourceLanguageId && $targetLanguageId) {
            $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
            $bind['sl'] = $sourceLanguageId;
            $bind['tl'] = $targetLanguageId;
        } elseif ($sourceLanguageId && !$targetLanguageId) {
            $lang = 'AND ww.sl = :sl';
            $bind['sl'] = $sourceLanguageId;
        } elseif (!$sourceLanguageId && $targetLanguageId) {
            $lang = 'AND ww.tl = :tl';
            $bind['tl'] = $targetLanguageId;
        }
        $orderC = '';
        if ($caseSensitive) { // 🔠🔡 Case Sensitive Search
            $orderC = 'COLLATE NOCASE';
        }
        $qword = 'AND sw.word = :sw ' . $orderC;
        if ($fuzzy) { // 💭 Fuzzy Search
            $qword = "AND sw.word LIKE '%' || :sw || '%' $orderC";
        }
        $bind['sw'] = $word;
        $sql = "$select
        FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
        WHERE sw.id = ww.sw AND tw.id = ww.tw
        AND   sl.id = ww.sl AND tl.id = ww.tl
        $lang $qword";
        $result = $this->attogram->database->query($sql, $bind);
        return $result[0]['count'];
    } // end function getCountSearchDictionary()

    /**
     * Search dictionaries
     * @param  string $word   The Word to search thereupon
     * @param  int    $sourceLanguageId  (optional) Source Language ID, defaults to 0
     * @param  int    $targetLanguageId  (optional) Target Language ID, defaults to 0
     * @param  bool   $fuzzy              (optional) 💭 Fuzzy Search, defaults to false
     * @param  bool   $caseSensitive      (optional) 🔠🔡 Case Sensitive Search, defaults to false
     * @param  int    $limit              (optional) Limit # of results per page, defaults to 100
     * @param  int    $offset             (optional) result # to start listing at, defaults to 0
     * @return array                      list of word pairs
     */
    public function searchDictionary($word, $sourceLanguageId = 0, $targetLanguageId = 0, $fuzzy = false, $caseSensitive = false, $limit = 100, $offset = 0)
    {
        $this->attogram->log->debug('searchDictionary: word=' . $this->attogram->webDisplay($word) . " sl=$sourceLanguageId tl=$targetLanguageId f=$fuzzy c=$caseSensitive limit=$limit offset=$offset");
        $this->insertHistory($word, $sourceLanguageId, $targetLanguageId);
        $select = 'SELECT sw.word AS s_word, tw.word AS t_word, sl.code AS sc, tl.code AS tc, sl.name AS sn, tl.name AS tn';
        $orderC = '';
        if ($caseSensitive) { // 🔠🔡 Case Sensitive Search
            $orderC = 'COLLATE NOCASE';
        }
        $order = "ORDER BY sw.word $orderC, sl.name $orderC, tl.name $orderC, tw.word $orderC";
        $lang = '';
        if ($sourceLanguageId && $targetLanguageId) {
            $lang = 'AND ww.sl = :sl AND ww.tl = :tl';
            $bind['sl'] = $sourceLanguageId;
            $bind['tl'] = $targetLanguageId;
        } elseif ($sourceLanguageId && !$targetLanguageId) {
            $lang = 'AND ww.sl = :sl';
            $bind['sl'] = $sourceLanguageId;
        } elseif (!$sourceLanguageId && $targetLanguageId) {
            $lang = 'AND ww.tl = :tl';
            $bind['tl'] = $targetLanguageId;
        }
        $qword = 'AND sw.word = :sw ' . $orderC;
        if ($fuzzy) { // 💭 Fuzzy Search
            $qword = "AND sw.word LIKE '%' || :sw || '%' $orderC";
        }
        $bind['sw'] = $word;
        if ($limit) {
            $sqlLimit = " LIMIT $limit";
            if ($offset) {
                $sqlLimit .= " OFFSET $offset";
            }
        }
        $sql = "$select
        FROM word2word AS ww, word AS sw, word AS tw, language AS sl, language AS tl
        WHERE sw.id = ww.sw AND tw.id = ww.tw
        AND   sl.id = ww.sl AND tl.id = ww.tl
        $lang $qword $order $sqlLimit";
        $result = $this->attogram->database->query($sql, $bind);
        return $result;
    } // end function searchDictionary()

    /**
     * insert an search history entry into the database
     * @param  string  $word   The Word
     * @param  int $sourceLanguageId   (optional) Source Language ID, defaults to 0
     * @param  int $targetLanguageId   (optional) Target Language ID, defaults to 0
     * @return bool
     */
    public function insertHistory($word, $sourceLanguageId = 0, $targetLanguageId = 0)
    {
        $now = gmdate('Y-m-d');
        if (!$sourceLanguageId || !is_int($sourceLanguageId)) {
            $sourceLanguageId = 0;
        }
        if (!$targetLanguageId || !is_int($targetLanguageId)) {
            $targetLanguageId = 0;
        }
        $this->attogram->log->debug('insertHistory: date: ' . $now . ' sl: ' . $sourceLanguageId . ' tl: ' . $targetLanguageId . ' word: ' . $this->attogram->webDisplay($word));
        $bind = array('word' => $word, 'sl' => $sourceLanguageId, 'tl' => $targetLanguageId, 'date' => $now);
        $resultid = $this->attogram->database->query('SELECT id FROM history WHERE word = :word AND date = :date AND sl = :sl AND tl = :tl', $bind);
        if (!$resultid) { // insert new history entry for this date
            return $this->attogram->database->queryb('INSERT INTO history (word, sl, tl, date, count) VALUES (:word, :sl, :tl, :date, 1)', $bind);
        }
        return $this->attogram->database->queryb(// update count
            'UPDATE history SET count = count + 1 WHERE id = :id',
            array('id' => $resultid[0]['id'])
        );
    } // end function insertHistory()

    /**
     * Import translations into the database
     * @param string $translations        List of word pairs, 1 pair to a line, with \n at end of line
     * @param string $deliminator         Deliminator
     * @param string $sourceLanguageCode  Source Language Code
     * @param string $targetLanguageCode  Target Language Code
     * @param string $sourceLanguageName  (optional) Source Language Name
     * @param string $targetLanguageName  (optional) Target Language Name
     */
    public function doImport($translations, $deliminator, $sourceLanguageCode, $targetLanguageCode, $sourceLanguageName = '', $targetLanguageName = '')
    {
        $this->attogram->log->debug("doImport: s=$sourceLanguageCode t=$targetLanguageCode d=$deliminator sn=$sourceLanguageName tn=$targetLanguageName w strlen=" . strlen($translations));
        $deliminator = str_replace('\t', "\t", $deliminator); // allow real tabs
        $sourceLanguageName = $this->getLanguageNameFromCode($sourceLanguageCode, /*$default =*/ $sourceLanguageName, /*$insert =*/ true); // The Source Language Name
        if (!$sourceLanguageName) {
            $error = 'Error: can not get source language name';
            print $error;
            $this->attogram->log->error("doImport: $error");
            return;
        }
        $sourceLanguageId = $this->getLanguageIdFromCode($sourceLanguageCode); // The Source Language ID
        if (!$sourceLanguageId) {
            $error = 'Error: can not get source language ID';
            print $error;
            $this->attogram->log->error("doImport: $error");
            return;
        }
        $targetLanguageName = $this->getLanguageNameFromCode($targetLanguageCode, /* $default =*/ $targetLanguageName, /* $insert =*/ true); // The Target Language Name
        if (!$targetLanguageName) {
            $error = 'Error: can not get source language name';
            print $error;
            $this->attogram->log->error("doImport: $error");
            return;
        }
        $targetLanguageId = $this->getLanguageIdFromCode($targetLanguageCode); // The Target Language ID
        if (!$targetLanguageId) {
            $error = 'Error: can not get target language ID';
            print $error;
            $this->attogram->log->error("doImport: $error");
            return;
        }
        $this->attogram->log->debug("do import: sn=$sourceLanguageName si=$sourceLanguageId tn=$targetLanguageName ti=$targetLanguageId");
        $lines = explode("\n", $translations);
        print '<div class="container">'
        .'Source Language: ID: <code>'.$sourceLanguageId.'</code>'
        .' Code: <code>'.$this->attogram->webDisplay($sourceLanguageCode).'</code>'
        .' Name: <code>'.$this->attogram->webDisplay($sourceLanguageName).'</code>'
        .'<br />Target Language:&nbsp; ID: <code>'.$targetLanguageId.'</code>'
        .' Code: <code>'.$this->attogram->webDisplay($targetLanguageCode).'</code>'
        .' Name: <code>'.$this->attogram->webDisplay($targetLanguageName).'</code>'
        .'<br />Deliminator: <code>'.$this->attogram->webDisplay($deliminator).'</code>'
        .'<br />Lines: <code>'.sizeof($lines).'</code><hr /><small>';
        $lineCount = $importCount = $errorCount = $skipCount = $dupeCount = 0;
        foreach ($lines as $line) {
            set_time_limit(240);
            $lineCount++;
            $line = urldecode($line);
            $line = trim($line);
            if ($line == '') {
                //print '<p>Info: Line #' . $lineCount . ': Blank line found. Skipping line</p>';
                $skipCount++;
                continue;
            }
            if (preg_match('/^#/', $line)) {
                  //print '<p>Info: Line #' . $lineCount . ': Comment line found. Skipping line.</p>';
                  $skipCount++;
                  continue;
            }
            if (!preg_match('/' . $deliminator . '/', $line)) {
                print '<p>Error: Line #' . $lineCount . ': Deliminator (' . $this->attogram->webDisplay($deliminator) . ') Not Found. Skipping line.</p>';
                $errorCount++;
                $skipCount++;
                continue;
            }
            $translationsp = explode($deliminator, $line);
            if (sizeof($translationsp) != 2) {
                print '<p>Error: Line #' . $lineCount . ': Malformed line.  Expecting 2 words, found ' . sizeof($translationsp) . ' words</p>';
                $errorCount++;
                $skipCount++;
                continue;
            }
            $sourceWord = trim($translationsp[0]); // The Source Word
            if (!$sourceWord) {
                print '<p>Error: Line #' . $lineCount . ': Malformed line.  Missing source word</p>';
                $errorCount++;
                $skipCount++;
                continue;
            }
            $sourceWordId = $this->getIdFromWord($sourceWord); // The Source Word ID
            if (!$sourceWordId) {
                print '<p>Error: Line #' . $lineCount . ': Can Not Get/Insert Source Word</p>';
                $errorCount++;
                $skipCount++;
                continue;
            }
            $targetWord = trim($translationsp[1]); // The Target Word
            if (!$targetWord) {
                print '<p>Error: Line #' . $lineCount . ': Malformed line.  Missing target word</p>';
                $errorCount++;
                $skipCount++;
                continue;
            }
            $targetLanguageId = $this->getIdFromWord($targetWord); // The Target Word ID
            if (!$targetLanguageId) {
                print '<p>Error: Line #' . $lineCount . ': Can Not Get/Insert Target Word</p>';
                $errorCount++;
                $skipCount++;
                continue;
            }
            $this->attogram->log->debug("doImport: sw=$sourceWord swi=$sourceWordId si=$sourceLanguageId tw=$targetWord twi=$targetLanguageId ti=$targetLanguageId");
            $result = $this->insertWord2word($sourceWordId, $sourceLanguageId, $targetLanguageId, $targetLanguageId);
            if (!$result) {
                if ($this->attogram->database->database->errorCode() == '0000') {
                    //print '<p>Info: Line #' . $lineCount . ': Duplicate.  Skipping line';
                    $errorCount++;
                    $dupeCount++;
                    $skipCount++;
                    continue;
                }
                print '<p>Error: Line #' . $lineCount . ': Database Insert Error';
                $errorCount++;
                $skipCount++;
            } else {
                $importCount++;
                $this->attogram->event->info(
                    'ADD translation: <code>' . $sourceLanguageCode . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($sourceLanguageCode)
                    . '//' . urlencode($sourceWord) . '">' . $this->attogram->webDisplay($sourceWord) . '</a>'
                    . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($targetLanguageCode) . '//' . urlencode($targetWord)
                    . '">' . $this->attogram->webDisplay($targetWord) . '</a> <code>' . $targetLanguageCode. '</code>'
                );
            }
            // insert reverse pair
            $result = $this->insertWord2word($targetLanguageId, $targetLanguageId, $sourceWordId, $sourceLanguageId);
            if (!$result) {
                if ($this->attogram->database->database->errorCode() == '0000') {
                    //print '<p>Info: Line #' . $lineCount . ': Duplicate.  Skipping line';
                    $errorCount++;
                    $dupeCount++;
                    $skipCount++;
                    continue;
                }
                print '<p>Error: Line #' . $lineCount . ': Database Insert Error';
                $errorCount++;
                $skipCount++;
            } else {
                $importCount++;
                $this->attogram->event->info(
                    'ADD translation: <code>' . $targetLanguageCode. '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($targetLanguageCode)
                    . '//' . urlencode($targetWord) . '">' . $this->attogram->webDisplay($targetWord) . '</a>'
                    . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($sourceLanguageCode) . '//' . urlencode($sourceWord)
                    . '">' . $this->attogram->webDisplay($sourceWord) . '</a> <code>' . $sourceLanguageCode . '</code>'
                );
            }
            if ($lineCount % 100 == 0) {
                print ' ' . $lineCount . ' ';
            } elseif ($lineCount % 10 == 0) {
                print '.';
            }
            @ob_flush();
            flush();
        } // end foreach line
        print '</small><hr />';
        print '<code>'.$importCount.'</code> translations imported.<br />';
        print '<code>'.$errorCount.'</code> errors.<br />';
        print '<code>'.$dupeCount.'</code> duplicates/existing.<br />';
        print '<code>'.$skipCount.'</code> lines skipped.<br />';
        print '</div>';
    } // end doImport

    /**
     * get count of entries in slush pile
     * @return int
     */
    public function getCountSlushPile()
    {
        $result = $this->attogram->database->query('SELECT count(id) AS count FROM slush_pile');
        if (!$result) {
            return 0;
        }
        return $result[0]['count'];
    }

    /**
     * add new entry to the slush pile
     * @param array $items  List of name=value pairs
     * @return bool
     */
    public function addToSlushPile(array $items = array())
    {
        if (!$items) {
            return false;
        }
        $names = array_keys($items);
        $sql = 'INSERT INTO slush_pile (date, ' . implode(', ', $names) . ')'
            . ' VALUES (datetime("now"), :' . implode(', :', $names) . ')';
        if ($this->attogram->database->queryb($sql, $items)) {
            //$this->attogram->event->info('ADD slush_pile', $items);
            return true;
        }
        return false;
    }

    /**
     * delete an entry from the slush pile
     * @param  int  $id  The slush_pile.id to delete
     * @return bool
     */
    public function deleteFromSlushPile($slushId)
    {
        // does slush pile entry exist?
        if (!$this->attogram->database->query('SELECT id FROM slush_pile WHERE id = :id LIMIT 1', array('id' => $slushId))) {
            $this->attogram->log->error('deleteFromSlushPile: Not Found id=' . $this->attogram->webDisplay($slushId));
            $_SESSION['error'] = 'Slush Pile entry not found (ID: ' . $this->attogram->webDisplay($slushId) . ')';
            return false;
        }
        if ($this->attogram->database->queryb('DELETE FROM slush_pile WHERE id = :id', array('id' => $slushId))) {
            return true;
        }
        $this->attogram->log->error('deleteFromSlushPile: Delete failed for id=' . $this->attogram->webDisplay($slushId));
        $_SESSION['error'] = 'Unable to delete Slush Pile entry (ID: ' . $this->attogram->webDisplay($slushId) . ')';
        return false;
    }

    /**
     * accept an entry from the slush pile for entry into the dictionary
     * @param  int  $id  The slush_pile.id to accept
     * @return bool
     */
    public function acceptSlushPileEntry($slushId)
    {
        $spe = $this->attogram->database->query(// get slush_pile entry
            'SELECT * FROM slush_pile WHERE id = :id LIMIT 1',
            array('id' => $slushId)
        );
        if (!$spe) {
            $this->attogram->log->error('acceptSlushPileEntry: can not find id=' . $this->attogram->webDisplay($slushId));
            $_SESSION['error'] = 'Can not find requested slush pile entry';
            return false;
        }
        $type = $spe[0]['type'];
        switch ($type) {
            case 'add': // add word2word translation
                $sourceWordId = $this->getIdFromWord($spe[0]['source_word']); // Source Word ID
                $sourceLanguageId = $this->getLanguageIdFromCode($spe[0]['source_language_code']); // Source Language ID
                $targetWordId = $this->getIdFromWord($spe[0]['target_word']); // Target Word ID
                $targetLanguageId = $this->getLanguageIdFromCode($spe[0]['target_language_code']); // Target Language ID
                if ($this->hasWord2Word($sourceWordId, $sourceLanguageId, $targetWordId, $targetLanguageId)) {
                    $this->deleteFromSlushPile($slushId); // dev todo - check results
                    $this->attogram->log->error('acceptSlushPileEntry: Add translation: word2word entry already exists. Deleted slush_pile.id=' . $this->attogram->webDisplay($slushId));
                    $_SESSION['error'] = 'Translation already exists!  Slush pile entry deleted (ID: ' . $this->attogram->webDisplay($slushId) . ')';
                    return false;
                }
                if ($this->insertWord2word($sourceWordId, $sourceLanguageId, $targetWordId, $targetLanguageId)) {
                    $this->attogram->event->info(
                        'ADD translation: <code>' . $spe[0]['source_language_code'] . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['source_language_code'])
                        . '//' . urlencode($spe[0]['source_word']) . '">' . $this->attogram->webDisplay($spe[0]['source_word']) . '</a>'
                        . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['target_language_code']) . '//' . urlencode($spe[0]['target_word'])
                        . '">' . $this->attogram->webDisplay($spe[0]['target_word']) . '</a> <code>' . $spe[0]['target_language_code'] . '</code>'
                    );
                    if ($this->insertWord2word($targetWordId, $targetLanguageId, $sourceWordId, $sourceLanguageId)) {
                          $this->attogram->event->info(
                              'ADD translation:  <code>' . $spe[0]['target_language_code'] . '</code> <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['target_language_code'])
                              . '//' . urlencode($spe[0]['target_word']) . '">' . $this->attogram->webDisplay($spe[0]['target_word']) . '</a>'
                              . ' = <a href="' . $this->attogram->path . '/word/' . urlencode($spe[0]['source_language_code']) . '//' . urlencode($spe[0]['source_word'])
                              . '">' . $this->attogram->webDisplay($spe[0]['source_word']) . '</a> <code>' . $spe[0]['source_language_code'] . '</code>'
                          );
                          $this->deleteFromSlushPile($slushId); // dev todo - check results
                          $_SESSION['result'] =
                              'Added new translation:  <code>' . $this->attogram->webDisplay($spe[0]['source_language_code']) . '</code> '
                              . '<a href="../word/' . urlencode($spe[0]['source_language_code']) . '/' . urlencode($spe[0]['target_language_code']) . '/' . urlencode($spe[0]['source_word']) . '">' . $this->attogram->webDisplay($spe[0]['source_word']) . '</a>'
                              . ' = '
                              . '<a href="../word/' . urlencode($spe[0]['target_language_code']) . '/' . urlencode($spe[0]['source_language_code']) . '/' . urlencode($spe[0]['target_word']) . '">' . $this->attogram->webDisplay($spe[0]['target_word']) . '</a>'
                              . ' <code>' . $this->attogram->webDisplay($spe[0]['target_language_code']) . '</code>';
                          return true;
                    }
                    $this->attogram->log->error('acceptSlushPileEntry: Can not insert reverse word2word entry');
                    $_SESSION['error'] = 'Failed to insert new reverse translation';
                    return false;
                }
                $this->attogram->log->error('acceptSlushPileEntry: Can not insert word2word entry');
                $_SESSION['error'] = 'Failed to insert new translation';
                return false;
            case 'delete': // DEV TODO -- delete word2word translation
            default: // unknown type
                $this->attogram->log->error('acceptSlushPileEntry: id=' . $this->attogram->webDisplay($slushId) . ' INVALID type=' . $this->attogram->webDisplay($type));
                $_SESSION['error'] = 'Invalid slush pile entry (ID: ' . $this->attogram->webDisplay($slushId) . ')';
                return false;
        } // end switch on type
        return false;
    } // end function acceptSlushPileEntry()

    /**
     * HTML display for a single translation word pair
     * @param  string  $sourceWord          The Source Word
     * @param  string  $sourceLanguageCode  The Source Language Code
     * @param  string  $targetWord          The Target Word
     * @param  string  $targetLanguageCode  The Target Language Code
     * @param  string  $path                (optional) URL path, defaults to ''
     * @param  string  $deliminator         (optional) The Deliminator, defaults to ' = '
     * @param  bool    $usc                 (optional) Put Language Source Code in word URLS, defaults to true
     * @param  bool    $utc                 (optional) Put Language Target Code in word URLs, defaults to false
     * @return string                       HTML fragment
     */
    public function displayPair($sourceWord, $sourceLanguageCode, $targetWord, $targetLanguageCode, $path = '', $deliminator = ' = ', $usc = true, $utc = false)
    {
        $sUrl = $path . '/word/' . ($usc ? $sourceLanguageCode : '') . '/' . ($utc ? $targetLanguageCode : '') . '/' . urlencode($sourceWord);
        $tUrl = $path . '/word/' . ($usc ? $targetLanguageCode : '') . '/' . ($utc ? $sourceLanguageCode : '') . '/' . urlencode($targetWord);
        $sourceWord = $this->attogram->webDisplay($sourceWord);
        $targetWord = $this->attogram->webDisplay($targetWord);
        $sourceLanguageName = $this->getLanguageNameFromCode($sourceLanguageCode);
        $targetLanguageName = $this->getLanguageNameFromCode($targetLanguageCode);
        $editUid = md5($sourceWord . $sourceLanguageCode . $targetWord . $targetLanguageCode);
        $result = '
        <div class="row" style="border:1px solid #ccc;margin:2px;">
          <div class="col-xs-6 col-sm-4 text-left">
            <a href="' . $sUrl . '" class="pair">' . $sourceWord . '</a>
          </div>
          <div class="col-xs-6 col-sm-4 text-left">
            ' . $deliminator . ' <a href="' . $tUrl . '" class="pair">' . $targetWord . '</a>
          </div>
          <div class="col-xs-8 col-sm-2 text-left">
           <code><small>' . $sourceLanguageName . ' ' . $deliminator . ' ' . $targetLanguageName . '</small></code>
          </div>
          <div class="col-xs-4 col-sm-2 text-center">
            <a name="editi' . $editUid . '" id="editi' . $editUid . '" href="javascript:void(0);"
              onclick="$(\'#edit' . $editUid . '\').show();$(\'#editi' . $editUid . '\').hide();">🔧</a>
            <div id="edit' . $editUid . '" name="edit" style="display:none;">
             <form name="tag' . $editUid . '" id="tag' . $editUid . '" method="POST" style="display:inline;">
               <input type="hidden" name="type" value="tag">
               <input type="hidden" name="tw" value="' . $targetWord . '">
               <input type="hidden" name="sl" value="' . $sourceLanguageCode . '">
               <input type="hidden" name="tl" value="' . $targetLanguageCode . '">
               <button type="send">⛓</button>
             </form>
             <form name="del' . $editUid . '" id="del' . $editUid . '" method="POST" style="display:inline;">
               <input type="hidden" name="type" value="del">
               <input type="hidden" name="tw" value="' . $targetWord . '">
               <input type="hidden" name="sl" value="' . $sourceLanguageCode . '">
               <input type="hidden" name="tl" value="' . $targetLanguageCode . '">
               <button type="send">❌</button>
             </form>
             </form>
            </div>
          </div>
        </div>';
        return $result;
    } // end function displayPair
} // end class ote
