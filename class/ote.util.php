<?php /*

Open Translation Engine

Util class

*/ 
class ote_util extends ote_database {

//////////////////////////////////////////////////////////////////////////////
function get_language_code($raw) {
	switch( $raw ) {
		case 1:
		case LANG_1_CODE:
		case LANG_1_NAME:
			return LANG_1_CODE;
		case 2:
		case LANG_2_CODE:
		case LANG_2_NAME:
			return LANG_2_CODE;
		default:
			return 'ERR';
	}
} // END get_language_code

//////////////////////////////////////////////////////////////////////////////
function get_language_name($raw) {
	switch( $raw ) {
		case 1:
		case LANG_1_CODE:
		case LANG_1_NAME:
			return LANG_1_NAME;
		case 2:
		case LANG_2_CODE:
		case LANG_2_NAME:
			return LANG_2_NAME;
		default:
			return 'ERROR';
	}
} // END get_language_by_id
 
//////////////////////////////////////////////////////////////////////////////
function get_word_by_id( $id ) {
	if( $id == '' ) { return ''; }
	$res = $this->query("SELECT word FROM ote_word WHERE id = '" 
	. mysql_real_escape_string( $id ) . "' LIMIT 1");
	if( $this->number_of_rows == 0 ) { 
		return ''; 
	} else { 
		return $res[1]->word; 
	}
} // END function get_word_by_id

//////////////////////////////////////////////////////////////////////////////
function get_word_id_by_name( $name ) {
	if( $name == '' ) { return 0; }
	$res = $this->query("SELECT id FROM ote_word WHERE word LIKE '"
	. mysql_real_escape_string( $name ) . "' LIMIT 1");
	if( !isset($res[1]->id) ) { return FALSE; }
	return $res[1]->id;
} // END get_word_id_by_name

//////////////////////////////////////////////////////////////////////////////
function get_links_by_word_id( $id ) {
	$res = $this->query("SELECT * FROM ote_word2word WHERE s_id = '"
	. mysql_real_escape_string( $id ) . "'");
	return $res;
} // END function get_links_by_word_id

//////////////////////////////////////////////////////////////////////////////
function get_reverse_links_by_word_id( $id ) {
	$res = $this->query("SELECT * FROM ote_word2word WHERE t_id = '"
	. mysql_real_escape_string( $id ) . "'");
	return $res;
} // END function get_reverse_links_by_word_id

//////////////////////////////////////////////////////////////////////////////
function get_user_by_id( $id ) {
	$res = $this->query("SELECT username FROM ote_user WHERE id = '"
	. mysql_real_escape_string( $id ) . "' LIMIT 1");
	if( $res[1]->username == '' || !$res[1]->username ) {
		return '-';
	} else { 
		return $res[1]->username;
	}
} // END function get_user_by_id

//////////////////////////////////////////////////////////////////////////////
function delete_word( $id ) {
	$this->raw_query("DELETE FROM ote_word WHERE id = '"
	. mysql_real_escape_string( $id ) . "' LIMIT 1");
	return 1;
} // END delete_word

//////////////////////////////////////////////////////////////////////////////
function add_word( $word ) {
	if( $existing_id = $this->get_word_id_by_name($word) ) {
		return $existing_id; 
	}
	if(get_magic_quotes_gpc()) {
		$word = stripslashes($word);
	}
	$this->raw_query("INSERT INTO ote_word (word) VALUES ('"
	. mysql_real_escape_string($word) . "')");
	return mysql_insert_id();
} // END function add_word

//////////////////////////////////////////////////////////////////////////////
function delete_word2word( $id ) {
	$this->raw_query("DELETE FROM ote_word2word WHERE id = '"
	. mysql_real_escape_string( $id ) . "' LIMIT 1");
	return 1;
} // END delete_word2word

//////////////////////////////////////////////////////////////////////////////
function add_word2word($source_word_text, $source_code, $target_word_text, $target_code) {
	$source_word_id = $this->get_word_id_by_name($source_word_text);
	$target_word_id = $this->get_word_id_by_name($target_word_text);
	$this->raw_query('INSERT INTO ote_word2word (s_id, t_id, s_code, t_code ) VALUES '
	. " ( '$source_word_id', '$target_word_id', '$source_code', '$target_code')");
	return mysql_insert_id();
} // END add_word2word

//////////////////////////////////////////////////////////////////////////////
function get_link_by_id( $id ) {
	$res = $this->query("SELECT * FROM ote_word2word WHERE id = '"
	. mysql_real_escape_string( $id ) . "'");
	return $res;
} // END get_link_by_id

//////////////////////////////////////////////////////////////////////////////
 function get_reverse_link(	$source_word_id, $source_code, $target_word_id, $target_code ) {
	$res = $this->query("SELECT id FROM ote_word2word
	WHERE t_id = '$source_word_id' AND s_id = '$target_word_id'
	AND t_code = '$source_code' AND s_code = '$target_code'");
	return $res[1]->id;
} // END get_reverse_link

//////////////////////////////////////////////////////////////////////////////
function get_word_pair_count($source_code, $target_code) {
	$r = $this->query("SELECT count(ww.id) AS pairs FROM ote_word2word AS ww
	WHERE ww.s_code = '$source_code' AND ww.t_code = '$target_code'");
	return $r[1]->pairs;
} // END get_word_pair_count

//////////////////////////////////////////////////////////////////////////////
function wordnotfound( $source_word, $source_code, $source_language_name, $target_language_name ) {
	header('HTTP/1.0 404 Not Found');
	$this->show_header( $this->__('404 - word not found') );
	$this->template_source_word = $source_word;
	$this->template_source_word_url = urlencode($source_word);
	$this->template_source_lang_code = $source_code;
	$this->template_source_lang_name = $source_language_name;
	$this->template_target_lang_name = $target_language_name;
	$this->template_target_lang_code = $this->get_language_code( $target_language_name );

	if( $_SESSION['level'] >= 1 ) { 
		print $this->get_template('404.word.not.found.html');
	}
	$this->show_footer();
	exit;
} // END wordnotfound

//////////////////////////////////////////////////////////////////////////////
function clean( $input, $method='alpha' ) {

	switch( $method ) {

		case 'alpha':
		default:

			$input = urldecode($input);

			if( !defined('DEPRECIATED_V4') ) {
				if( !$this->database_link ) { $this->connect(); }
				$input = mb_eregi_replace(">", "&gt;", $input);
				$input = mb_eregi_replace("<", "&lt;", $input);
				if( defined('FORCE_LOWERCASE') ) { $input = mb_strtolower($input, 'UTF-8'); }
			}	else {
				$input = eregi_replace(">", "&gt;", $input);
				$input = eregi_replace("<", "&lt;", $input);
				if( defined('FORCE_LOWERCASE') ) { $input = strtolower($input); }
			}

			$input = trim($input);
			$input = rtrim($input);
			$input = str_replace('    ',' ',$input);
			$input = str_replace('   ',' ',$input);
			$input = str_replace('  ',' ',$input);

			break;

		case 'email':
		case 'password':
			if( !defined('DEPRECIATED_V4') ) {
				$input = mb_ereg_replace("[^A-Za-z0-9@_+=%,;:./-]", "", $input);
			} else {
				$input = ereg_replace("[^A-Za-z0-9@_+=%,;:./-]", "", $input);
			}
			break;

		case 'username':
			if( !defined('DEPRECIATED_V4') ) {
				$input = mb_ereg_replace("[^A-Za-z0-9]", "", $input);
			} else {
				$input = ereg_replace("[^A-Za-z0-9]", "", $input);
			}
			break;

		case 'translate': // remove punctuation
			if( defined('CLEAN_PUNCTUATION') ) {
				$punctuation_to_remove = constant('CLEAN_PUNCTUATION');
				if( !defined('DEPRECIATED_V4') ) {
					$input = mb_ereg_replace("[$punctuation_to_remove]", "", $input);
					if( defined('FORCE_LOWERCASE') ) { $input = mb_strtolower($input, 'UTF-8'); }
				} else {
					$input = ereg_replace("[$punctuation_to_remove]", "", $input);
					if( defined('FORCE_LOWERCASE') ) { $input = strtolower($input); }
				}
			}

			break;
	} // end switch
	return $input;
} // END clean

//validate email from the regex
function valid_email( $email ) {
    if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$email)) {
        return false;
    }
    else {
        return true;
    } //END if
} //END valid_email




} // END class ote_util
