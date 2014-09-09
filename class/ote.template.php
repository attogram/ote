<?php /*

Open Translation Engine

Template class

*/ 
class ote_template extends ote_translate {

//////////////////////////////////////////////////////////////////////////////
function get_user_level_name( $level ) {
	$this->level_name = $this->level0 = $this->level1 = $this->level5 = $this->level9 = '';
	switch( $level ) {
		case '0': $this->level_name = $this->__('guest'); $this->level0 = ' checked '; break;
		case '1': $this->level_name = $this->__('user'); $this->level1 = ' checked '; break;
		case '5': $this->level_name = $this->__('dictionary admin'); $this->level5 = ' checked '; break;
		case '9': $this->level_name = $this->__('site admin'); $this->level9 = ' checked '; break;
		default: $this->level_name = $this->__('error'); break;
	}
} // END function get_user_level_name

//////////////////////////////////////////////////////////////////////////////
function show_header($title='', $use_feed_url=0, $nopage=0) {
	if( !$_SESSION['id'] || is_nan( $_SESSION['id'] ) ) { // is Anonymous Guest?
		$_SESSION['username'] = '';
		$_SESSION['id'] = 0;
		$level_check = 0; 
		if( ALLOW_ANONYMOUS_GUESTS_TO_SUGGEST == 1 ) { $level_check = 1; }
		$_SESSION['level'] = $level_check;
		if( !strstr($_SERVER['REQUEST_URI'], '/user/login/') // if not on user pages
			&& !strstr($_SERVER['REQUEST_URI'], '/user/register/') ) {
			if( defined('GUEST_ALERT') ) {
				$_SESSION['alert'] .= constant('GUEST_ALERT');
			}
		} // end if not on user pages
	} // end if Anonymous Guest
	if( $title ) {
		$page_title = $title;
		if( $nopage ) {
			$page_header = '&nbsp;';
		} else {
			$page_header ='<h1>' . $page_title . '</h1>';
		}
		$page_title .= ' - ';
	} else {
		$page_header = '<h1>' . $this->__('open translation engine') . ' ' . VERSION . '<h1>';
	}
	$page_title .= $this->__('open translation engine') . ' ' . VERSION;
	header('Content-type: text/html; charset=UTF-8');
	print $this->get_template('header.html', $page_title, $page_header);
} // end of header()

//////////////////////////////////////////////////////////////////////////////
function show_footer() {
	print $this->get_template('footer.html', $page_title='', $page_header='', $_SESSION['alert']);
	$_SESSION['alert'] = NULL;
	if( extension_loaded('zlib') ){
		ob_end_flush();  // flush out to the browser, if possible
	}
} // END function show_footer

//////////////////////////////////////////////////////////////////////////////
function get_template_dropdown() {
	if( !is_array($this->template_list) ) {
		return '<p>ERROR: no template list found from settings.php</p>';
	}
	if( sizeof( $this->template_list ) == 1 ) { return; }
	reset($this->template_list);
	$r .= '<form action="' . HOSTNAME . '" method="GET"><select name="view">';
	$count = 0;
	while( list($trash,$x) = each($this->template_list) ) {
		$count++;
		$sel = ''; if( $_SESSION['template_id'] == $count ) { $sel = ' selected="selected" '; }
		$r .= '<option value="' . $count . '" ' . $sel . '>' . $x['name'] . '</option>';
	} 
	$r .= '</select><input type="submit" value="Go"></form>';
	return $r;
} // end of get_template_dropdown

//////////////////////////////////////////////////////////////////////////////
function get_template( $template, $page_title='', $page_header='', $alert='' ) {
	$file = $_SERVER['DOCUMENT_ROOT'] . OTE_DIRECTORY . 'templates/' . TEMPLATE_DIRECTORY . '/' . $template;
	if( !file_exists($file) ) {
		$alert = "<p>ERROR: can't load template '" . htmlentities($file) . "'"
			. '<br />TEMPLATE_DIRECTORY: ' . TEMPLATE_DIRECTORY
			. '<br />template ID: ' . $template
			. '</p>';
		$do_not_include_settings_file = 1;
		include('error.check.php');
		exit;
	}
	$r = file_get_contents($file);

// simple template replacers
$template_replacers = array(
'<<VERSION>>'=>VERSION,
'<<HOSTNAME>>'=>HOSTNAME,
'<<LANG_1_CODE>>'=>LANG_1_CODE,
'<<LANG_2_CODE>>'=>LANG_2_CODE,
'<<LANG_1_NAME>>'=>LANG_1_NAME,
'<<LANG_2_NAME>>'=>LANG_2_NAME, 
'<<PAGE_TITLE>>'=>$page_title,
'<<PAGE_HEADER>>'=>$page_header,
'<<USER_EMAIL>>'=>$_SESSION['email'],
'<<USER_CREATED>>'=>$_SESSION['created'],
'<<USER_LAST_LOGIN>>'=>$_SESSION['last_login'],
'<<FEED_URL>>'=>$this->template_feed_url,
'<<PERMALINK>>'=>$this->template_permalink, 
'<<SOURCE_LANG_NAME>>'=>$this->template_source_lang_name,
'<<SOURCE_LANG_CODE>>'=>$this->template_source_lang_code,
'<<TARGET_LANG_NAME>>'=>$this->template_target_lang_name,
'<<TARGET_LANG_CODE>>'=>$this->template_target_lang_code,
'<<SOURCE_WORD>>'=>$this->template_source_word,
'<<TARGET_WORD>>'=>$this->template_target_word,
'<<SOURCE_WORD_URL>>'=>$this->template_source_word_url,
'<<TARGET_WORD_URL>>'=>$this->template_target_word_url,
'<<SUGGESTED>>'=>$this->template_suggested,
'<<WORD_BLOCK>>'=>$this->template_word_block,
'<<LINK_ID>>'=>$this->template_link_id,
'<<WORD_ROWS>>'=>$this->template_word_rows,
'<<WORD_ADD>>'=>$this->template_word_add,
'<<WORD_DELETE>>'=>$this->template_word_delete,
'<<DELIMITER_NAME>>'=>$this->template_delimiter_name,
'<<NUMBER_OF_ROWS>>'=>$this->template_number_of_rows,
'<<DATETIME>>'=> date('r') . ' GMT',
'<<QUICKBOX_ARRAY>>'=>$this->template_quickbox_array,
'<<Q>>'=>$this->template_q,
'<<Q_URL>>'=>$this->template_q_url,
'<<BGCOLOR>>'=>$this->template_bgcolor,
'<<TASK_TYPE>>'=>$this->template_task_type,
'<<ADD>>'=>$this->template_add,
);


	// complicated template replacers

	if(strpos($r,'<<TEMPLATE_MENU>>')!==false){
		$template_replacers['<<TEMPLATE_MENU>>'] = $this->get_template_dropdown();
	}

	if(strpos($r,'<<COUNT_1_2>>')!== false){
		$template_replacers['<<COUNT_1_2>>'] = $this->get_word_pair_count(LANG_1_CODE,LANG_2_CODE);
	}

	if(strpos($r,'<<COUNT_2_1>>')!== false){
		$template_replacers['<<COUNT_2_1>>'] = $this->get_word_pair_count(LANG_2_CODE,LANG_1_CODE); 
	}

	if(strpos($r,'<<ALERT>>')!== false){
		if( $alert ) {
			$template_replacers['<<ALERT>>'] = '<p class="alert">' . $alert . '</p>';
		} else {
			if( $_SESSION['alert'] != '' ) {
				$template_replacers['<<ALERT>>'] = '<p class="alert">' . $_SESSION['alert'] . '</p>'; 
			}
		}
	}

	$username = $this->clean( $_SESSION['username'], 'username' );
	$template_replacers['<<USERNAME>>'] = $username;

	if( $username == '' ) { // if not logged in
		$login = '<a href="' . HOSTNAME . 'user/login/">';
		$login .= $this->__('login');
		$login .= '</a>';
		$register = '<a href="' . HOSTNAME . 'user/register/">';
		$register .= $this->__('register');
		$register .= '</a>';
		$logout = '';
		$level_name = '';
		$user_menu = '';
		// end if not logged in
	} else { // if logged in
		$login = '';
		$register = '';
		$logout = '<a href="' . HOSTNAME . 'user/logout">';
		$logout .= $this->__('logout');
		$logout .= '</a>';
		$this->get_user_level_name( $_SESSION['level'] );
		if( $_SESSION['level'] == 9 ) {
			$user_menu = '<a href="' . HOSTNAME . 'admin/users/">Users</a> &nbsp; &nbsp; ';
		}
		$user_menu .= ''
		. '<a href="' . HOSTNAME . 'task/">Tasks</a>'
		. ' &nbsp; &nbsp; '
		. $this->level_name . ': ' 
		. '<a href="' . HOSTNAME . 'user/"><b>' . $username . '</b></a>';
	} // end if logged in

	if(strpos($r,'<<LOGIN>>')!== false){ $template_replacers['<<LOGIN>>'] = $login; }
	if(strpos($r,'<<LOGOUT>>')!== false){ $template_replacers['<<LOGOUT>>'] = $logout; }
	if(strpos($r,'<<REGISTER>>')!== false){ $template_replacers['<<REGISTER>>'] = $register; }
	if(strpos($r,'<<USER_MENU>>')!== false){ $template_replacers['<<USER_MENU>>'] = $user_menu; }
	if(strpos($r,'<<USER_NAME>>')!== false){ $template_replacers['<<USER_NAME>>'] = $username; }
	if(strpos($r,'<<LEVEL_NAME>>')!== false){ $template_replacers['<<LEVEL_NAME>>'] = $this->level_name; }



	preg_match_all("/<<([^>>]+)>>/U", $r, $matches); // get all template <<VARIABLES>> to be replaced with content...
	$matches = array_unique($matches[0]); // get only the unique <<VARIABLES>>
	while( $x = each($matches) ) { // loop thru all <<VARIABLES>>
		if( $template_replacers[ $x[1] ] ) { // if <<VARIABLE> is defined.. 
			$r = str_replace($x[1], $template_replacers[ $x[1] ], $r); // replace it with content
		} else { // if <<VARIABLE>> not defined...
			//$r = str_replace($x[1], htmlentities($x[1]) . '!!', $r); // ERROR
			$r = str_replace($x[1],'', $r); // replace with NULL
		} // end if defined
	} // end looping thru all <<VARIABLES>>

	//$r .= '<hr><textarea rows="10" cols="90">' 
	//. $template . ' $template_replacers = ' . $this->xdebug($template_replacers)  . '</textarea>';

	return $r;

} // end function get_template

//////////////////////////////////////////////////////////////////////////////
function force_slash_at_end( $exception='' ) {
	$request_uri = $_SERVER['REQUEST_URI'];
	if( $exception ) {
		if( $_GET[ $exception ] ) {
			return;
		}
	}
	if( $request_uri[strlen($request_uri)-1] != '/' ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . PROTOCOL . HOST . $request_uri . '/');
		exit;
	}
} // end of function force_slash_at_end

//////////////////////////////////////////////////////////////////////////////
function check_if_url_too_long( $depth=0, $redir='' ) {
	$uri = explode('/', $_SERVER['REQUEST_URI']);
	if( isset( $uri[ $depth + DEPTH ] ) ) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . HOSTNAME . $redir); exit;
	}
} // end function check_if_url_too_long

//////////////////////////////////////////////////////////////////////////////
function __( $msg ) {
	$r = $this->text[ $msg ]; // attempt to get translation for this message from 
	// array of text messages, from template.settings.php file
	if( !$r || $r == '' ) { // if no translation found..
		$r = $msg; // then just repeat the original message
		$r .= '--ERROR_NO_TRANSLATION_FOUND'; // DEBUG
	}
	return $r;
} // end of function __()

} // end of class ote_template