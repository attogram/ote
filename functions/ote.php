<?php
/*
 * Open Translation Engine (OTE)
 * powered by Attogram Framework
*/
namespace Attogram;

define('OTE_VERSION', '1.0.0-dev');

/**
 * Open Translation Engine (OTE) class
 */  
class ote {
  
  public $db;
  public $normalized_language_pairs;

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
    
  // TODO list($s,$t) = $this->normalize_language_pair($s_code,$t_code);
        
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
    $limit = ' LIMIT 0,100'; // dev
    $sql .= $limit;
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
    
    // TODO list($s,$t) = $this->normalize_language_pair($s_code,$t_code);
    
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

    //$w = $_POST['w']; // List of word pairs

    //$d = $_POST['d']; // Deliminator
    $d = str_replace('\t', "\t", $d); // allow real tabs

    //$s = trim($_POST['s']); // Source Language Code
    $sn = $this->get_language_name_from_code($s, $default=$sn);

    //$t = trim($_POST['t']); // Target Language Code
    $tn = $this->get_language_name_from_code($t, $default=$tn);

    $lines = explode("\n", $w);
    print '<div class="container">';
    print 'Source Language: Code: <code>' . htmlentities($s) . '</code> Name: <code>' . htmlentities($sn) . '</code><br />';
    print 'Target Language: Code: <code>' . htmlentities($t) . '</code> Name: <code>' . htmlentities($tn) . '</code><br />';
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
      
      $bind = array( 's_id'=>$si, 's_code'=>$s, 't_id'=>$ti, 't_code'=>$t);

      // check if REVERSE pair already exists...
      $sql = '
        SELECT s_id 
        FROM word2word
        WHERE s_id = :t_id
        AND t_id = :s_id
        AND s_code = :t_code
        AND t_code = :s_code
        LIMIT 1
      ';
      $check = $this->db->query($sql,$bind);
      if( $check ) {
        //print '<p>Info: Line #' . $line_count . ': Duplicate (reverse). Skipping line';
        $error_count++; $dupe_count++; $skip_count++;
        continue;
      }
      
      $sql = '
        INSERT INTO word2word (
          s_id, s_code, t_id, t_code
        ) VALUES (
          :s_id, :s_code, :t_id, :t_code
        )';
      $r = $this->db->queryb($sql, $bind);
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
  * @return array An array of the normlized order (source_code, language_code)
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
    // normalize the table....
    print "<pre>TODO: normalize_word2word_table( $s_code, $t_code )</pre>";
    
    // find all reverse entries:  where s_code=$t_code and t_code=$s_code
    //  update entries reverse entries to normal: switch s_id/t_id and switch s_code/t_code
    
    $sql = '
    UPDATE word2word 
    SET s_code = t_code,
        t_code = s_code,
        s_id = t_id,
        t_id = s_id
    WHERE s_code = :s_code AND t_code = :t_code
    ';
    $bind = array( 's_code'=>$t_code, 't_code'=>$s_code );
    $r = $this->db->queryb($sql,$bind);
    if( !$r ) {
      print "<pre>TODO: catch error normalize_word2word_table( $s_code, $t_code )</pre>";
    }
    
    return array($s_code, $t_code);
  }

} // end class ote


