<?php /*

Open Translation Engine

Translate class

*/ 
class ote_translate extends ote_util {

//////////////////////////////////////////////////////////////////////////////
function translate($q, $source_language, $target_language, $limit='' ) {
	if( !is_integer($limit) ) { $limit=500; }
	$limit_clause = "LIMIT 0,$limit";
	$res = $this->query("SELECT tw.word AS target_word
					FROM ote_word AS sw, 
						ote_word AS tw, 
						ote_word2word AS ww
					WHERE sw.word = '" . mysql_real_escape_string($q) . "'
					AND ww.s_code = '" . mysql_real_escape_string($source_language) . "'
					AND ww.t_code = '" . mysql_real_escape_string($target_language) . "'
					AND sw.id = ww.s_id
					AND tw.id = ww.t_id
					
					ORDER BY tw.word
					$limit_clause");
	if( $this->number_of_rows == 0 ) { return; }
	$return_array = array();
	while( list($trash,$x) = each($res) ) {
		array_push($return_array, $x->target_word );
	}
	return $return_array;
 } // END translate

//////////////////////////////////////////////////////////////////////////////
function display_word_bit( $target_word, $target_code ) {
	$link = TRUE;
	$r .= '<h2>';
	if( $link ) { 
		$r .= '<a href="' . HOSTNAME . 'word/' . $this->get_language_code($target_code) 
		. '/' . urlencode($target_word). '">';
	}
	$r .= $target_word;
	if( $link ) { 
		$r .= '</a>'; 
	}
	$r .= '</h2>';
	return $r;
} // END display_word_bit

//////////////////////////////////////////////////////////////////////////////
function display_word($resultset) {

	if( !$resultset ) { return; }

	$target_language_code = 
	$this->template_target_lang_code = $resultset[1]->target_language_code ;

	$target_language_name = 
	$this->template_target_lang_name = $this->get_language_name( $resultset[1]->target_language_code );

	$source_word = 
	$this->source_word =  // for random..
	$this->template_source_word = $resultset[1]->source_word;

	$this->template_source_word_url = urlencode($this->template_source_word);

	$source_language_code = 
	$this->source_language_code = // for random..
	$this->template_source_lang_code = $resultset[1]->source_language_code;

	$source_language_name = 
	$this->template_source_lang_name = $this->get_language_name( $resultset[1]->source_language_code );

	while( list($trash,$x) = each($resultset) ) {

		$this->template_target_word = $x->target_word;
		$this->template_target_word_url = urlencode($x->target_word);

		if( $_SESSION['level'] > 0 ) { 
			$this->template_link_id = $x->link_id;
			$this->template_word_delete = $this->get_template('word.del.html');
		}

		$this->template_word_rows .= $this->get_template('word.row.html');

	} // end while each resultset

	if( $_SESSION['level'] > 0 ) { // Add Word (show if allowed)
		$this->template_word_add = $this->get_template('word.add.html');
	} // end of Add Word (show if allowed)

	$suggested = 
	$this->template_suggested = $this->display_suggested_target_word( $source_word, $target_language_code );

	$t .= $this->get_template('word.block.html');

	return $t;

} // end function display_word

////////////////////////////////////////////////////////////////////////////// 
function display_suggested_target_word( $word, $language_id ) {
	if( !is_array($_SESSION['suggestions']) ) { return $r; }
	reset( $_SESSION['suggestions'] );
	while( $x = each( $_SESSION['suggestions'] ) ) {
		if( $x['value'][0] == $word && $x['value'][1] == $language_id ) {
			$r .= '<br /><font color="#999999">suggested:</font> ' . $x['value'][2];
		} // end if
	} // end while
	return $r;
} // end display_suggested_target_word

} // end of class ote_translate