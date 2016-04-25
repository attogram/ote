<?php /*

Open Translation Engine

Task class


*/ 
class ote_task extends ote_template {

//////////////////////////////////////////////////////////////////////////////
function tasker() {

	if( $_POST ) { 
		$this->do_post_task(); 
	}

	$this->force_slash_at_end();

	$request_uri = $_SERVER['REQUEST_URI'];
	$uri = explode('/', $request_uri );

	// Error: url too long - redir to /task/list/
	if( isset($uri[5+DEPTH]) ) { 
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . HOSTNAME . 'task/list/'); exit;
	}

	switch( $uri[2+DEPTH] ) {
		case 'accept': $this->accept_task( $uri[3+DEPTH] ); break;
		case 'delete': $this->delete_task( $uri[3+DEPTH] ); break;
		case 'open': $this->show_open(); break;
		case 'closed': $this->show_closed(); break;
		default: case '': $this->do_task_list(); break;
	} // end switch

} // END function tasker

//////////////////////////////////////////////////////////////////////////////
function check_permissions( $level=1 ) {
	if( $_SESSION['level'] < $level ) { 
		$_SESSION['alert'] = 'Task Permission Denied. Level <b>' . $level . '</b> permission required.'
		. ' You are Level <b>' . $_SESSION['level'] . '</b><br />';
		$this->do_error();
	}
	return;
} // end check_permissions

//////////////////////////////////////////////////////////////////////////////
function do_error() {
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . HOSTNAME . 'task/list/' ); exit;
}

//////////////////////////////////////////////////////////////////////////////
function do_post_link_new() {

	$this->check_permissions(1); 

	$source_word_text =	$this->clean( $_POST['source_word'] );
	if ( !$source_word_text || $source_word_text == '' ) {
		$_SESSION['alert'] .= 'Error - No Source Word or invalid Source Word<br />'; return;
	} 

	$target_word_text	 = $this->clean( $_POST['target_word'] );
	if ( !$target_word_text || $target_word_text == '' ) {
		$_SESSION['alert'] .= 'Error - No Target Word or invalid Target Word<br />'; return;
	}

	$source_code = $_POST['source_language_code'];
	if ( $source_code == '' ) {
		$_SESSION['alert'] .= 'Error - No Source Language ID<br />'; return;
	}

	$target_code = $_POST['target_language_code'];
	if ( $target_code == '' ) {
		$_SESSION['alert'] .= 'Error - No Target Language ID<br />'; return;
	}

	$source_word_id = $this->get_word_id_by_name( $source_word_text );

	$target_word_id = $this->get_word_id_by_name( $target_word_text );

	$session_check_zero = 0;
	if( ALLOW_ANONYMOUS_GUESTS_TO_SUGGEST ) { $session_check_zero = -1; }
	if( $_SESSION['level'] >= 5 ) { // Level 5 or greater: Dictionary Admin
		if( !$source_word_id ) { $source_word_id = $this->add_word($source_word_text); }
		if( !$target_word_id ) { $target_word_id = $this->add_word($target_word_text); }
		$link_id = $this->add_word2word($source_word_text, $source_code, $target_word_text, $target_code);
		$z = $this->insert_task( 'CLOSED', 'ADD', $source_word_id, 
				 $source_word_text, $source_code, $target_word_id, 
				 $target_word_text, $target_code, $link_id);
		$link_id = $this->add_word2word( $target_word_text, $target_code, $source_word_text, $source_code);
		$z = $this->insert_task('CLOSED', 'ADD', $target_word_id, 
				 $target_word_text, $target_code, $source_word_id, 
				 $source_word_text, $source_code, $link_id);
	} elseif( $_SESSION['level'] < 5 && $_SESSION['level'] > $session_check_zero ) { // Level 1,2,3,4: Suggest Edits only
		$z = $this->insert_task( 'OPEN', 'ADD', $source_word_id, 
				 $source_word_text, $source_code, $target_word_id, 
				 $target_word_text, $target_code, $link_id=0);
		$z = $this->insert_task('OPEN', 'ADD', $target_word_id, 
				 $target_word_text, $target_code, $source_word_id, 
				 $source_word_text, $source_code, $link_id=0);
		// display suggestions from USER during this session
		if( !is_array($_SESSION['suggestions']) ) { $_SESSION['suggestions'] = array(); }
		array_push( $_SESSION['suggestions'], array($source_word_text, $target_code, $target_word_text ) );
		if( strstr($_SERVER['HTTP_REFERER'], '/random/') ) {
			if( $_POST['source_word'] ) {
				$_SESSION['override'] = $source_word_id; // $this->get_word_id_by_name( $_POST[source_word] );
			} else {
				$_SESSION['override'] = $source_word_id;
			}
		}
	} else {		// Level 0: View Only	
		$_SESSION['alert'] .= 'Error: You do not have permission to add or suggest edits<br />'; 
		return;
	}	// end of permission checks
}	// end of function do_post_link_new

//////////////////////////////////////////////////////////////////////////////
function do_post_link_del( $link_id ) {
	$this->check_permissions(1);
	$link = $this->get_link_by_id( $link_id );
	$link = $link[1];
	$source_word_id = $link->s_id; 
	$this->source_word_id = $source_word_id;
	$source_word_text = $this->get_word_by_id( $source_word_id );
	$source_code = $link->s_code;
	$target_word_id = $link->t_id;
	$target_word_text = $this->get_word_by_id( $target_word_id );
	$target_code = $link->t_code;
	if( $_SESSION['level'] >= 5 ) {	 // Level 5 or greater: Dictionary Admin
		$this->delete_word2word( $link_id );
		$this->insert_task( 'CLOSED', 'DEL', 
			$source_word_id, $source_word_text, $source_code,
			$target_word_id, $target_word_text, $target_code, $link_id);
		// delete reverse link - find reverse link
		$reverse_link_id = $this->get_reverse_link( $source_word_id, $source_code,
			$target_word_id, $target_code );
		if( !$reverse_link_id ) { return; }	// if no link, stop
		$this->delete_word2word( $reverse_link_id ); // delete reverse link
		$this->insert_task( 'CLOSED', 'DEL', 
			$target_word_id, $target_word_text, $target_code,
			$source_word_id, $source_word_text, $source_code,
			$reverse_link_id);
	} elseif( $_SESSION['level'] < 5 && $_SESSION['level'] > 0 ) {	// Level 1,2,3,4: Suggest Edits only	
		$this->insert_task( 'OPEN', 'DEL', 
			$source_word_id, $source_word_text, $source_code,
			$target_word_id, $target_word_text, $target_code, $link_id);
		// delete reverse link - find reverse link
		$reverse_link_id = $this->get_reverse_link( $source_word_id, $source_code, $target_word_id, $target_code );
		if( !$reverse_link_id ) { return; }	// if no link, stop
		$this->insert_task( 'OPEN', 'DEL', 
			$target_word_id, $target_word_text, $target_code,
			$source_word_id, $source_word_text, $source_code,
			$reverse_link_id);
	} else {	// Level 0: View Only	
		$_SESSION['alert'] .= 'Error: You do not have permission to delete or suggest deletes<br />'; 
		return;
	}	// end of permission checks
}	// end of function do_post_link_del

//////////////////////////////////////////////////////////////////////////////
function do_post_task() {
	if ( $_POST[link] == 'add' || $_POST[link] == 'new' ) {
		if ( $_POST[target_word] ) {
			$this->do_post_link_new();
		} else {
			$_SESSION['alert'] .= 'Please enter a word<br />';
		}
	} elseif ( $_POST[link] == 'del' ) {
		$this->do_post_link_del( $_POST['link_id'] );
	} else {
		 $_SESSION['alert'] .= 'TASK ERROR: _POST ' . $this->xdebug($_POST) . '<br />';
	}
	// Redirect back to referering page...
	if( !$_SERVER['HTTP_REFERER'] ) {
		$url = HOSTNAME;
	} else { 
		$url = $_SERVER['HTTP_REFERER']; 
	}
	// exception for random system
	if( strstr($_SERVER['HTTP_REFERER'], '/random/') ) {
		if( $_POST['source_word'] ) {
			$_SESSION['override'] = $this->get_word_id_by_name( urldecode($_POST[source_word]) );
		} else {
			$_SESSION['override'] = $this->source_word_id;
		}
		

	}
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $url);
	exit;
} // END of function do_post_task

//////////////////////////////////////////////////////////////////////////////
function insert_task(
	 $status, // OPEN, CLOSED
	 $mode, // ADD, DEL
	 $source_word_id, $source_word_text='', $source_code,
	 $target_word_id=NULL, $target_word_text='', $target_code=NULL, $link_id=NULL
 ) {
	$this->check_permissions();
	$user_id = $_SESSION['id'];
	$this->raw_query("INSERT INTO ote_task ( 
	status, mode,
	s_id, s_code, s_word,
	t_id, t_code, t_word,
	started, updated,
	user_id, admin_id
	) VALUES ( 
	'$status', '$mode',
	'$source_word_id', '$source_code', '" . mysql_real_escape_string($source_word_text) . "',
	'$target_word_id', '$target_code', '" . mysql_real_escape_string($target_word_text) . "',
	NOW(), NOW(),
	'$user_id', '" . $_SESSION['id'] . "' )"); 
	if( $_SESSION['level'] < 5 ) {
		$_SESSION['alert'] .= '<b>Suggestion Saved. </b>';
	} 
	$_SESSION['alert'] .= '<a href="' . HOSTNAME . 'task/list/">TASK</a>: ' . " $mode $action ";
	if( $source_word_text ) {
		$_SESSION['alert'] .= '(' . $this->get_language_code( $source_code ) 
		. ') <b>' . $source_word_text . '</b>';
	}
	if( $target_word_text ) {
		$_SESSION['alert'] .= ' = <b>' . $target_word_text . '</b> ('
		.	$this->get_language_code( $target_code ) . ')';
	}
	$_SESSION['alert'] .= '<br />';
	return mysql_insert_id();
} // end function insert_task

//////////////////////////////////////////////////////////////////////////////
function delete_task( $id ) {
	$this->check_permissions(5);
	$this->raw_query('DELETE FROM ote_task WHERE id = ' . $id . ' LIMIT 1');
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: ' . HOSTNAME . 'task/list/');
	exit;
} // end of function delete_teask

//////////////////////////////////////////////////////////////////////////////
function accept_task( $id ) {
	$this->check_permissions(5);
	$task = $this->get_task_by_id( $id );
	switch( $task->mode ) {
		case 'ADD':
			if( $task->s_id == 0 ) { 
				$task->s_id = $this->add_word( $task->s_word );
			}
			if( $task->t_id == 0 ) { 
				$task->t_id = $this->add_word( $task->t_word );
			}
			$this->add_word2word( $task->s_word, $task->s_code, $task->t_word, $task->t_code );
			break;
		case 'DEL':
			$reverse_link_id = $this->get_reverse_link(
				$task->s_id, $task->s_code, $task->t_id, $task->t_code );
			$forward_link_id = $this->get_reverse_link(
				$task->t_id, $task->t_code, $task->s_id, $task->s_code );
			$this->delete_word2word( $forward_link_id );
			$this->delete_word2word( $reverse_link_id );
			break;
	} // end switch on action
	$this->change_task_status( 'CLOSED', $id );
} // end of function close_task

//////////////////////////////////////////////////////////////////////////////
function change_task_status( $status, $id ) {
	$this->check_permissions(5);
	$this->raw_query('UPDATE ote_task 
	SET status = "' . $status. '",
	admin_id = "' . $_SESSION['id'] . '",
	updated = NOW()
	WHERE id = ' . $id . ' LIMIT 1');
	header("HTTP/1.1 301 Moved Permanently");
	header('Location: ' . HOSTNAME . 'task/list/');
	exit;
} // end function change_task_status

//////////////////////////////////////////////////////////////////////////////
function get_task_by_id( $id ) {
	$res = $this->query('SELECT * FROM ote_task WHERE id = ' . $id . ' LIMIT 1');
	return $res[1];
} // END function get_task_by_id

//////////////////////////////////////////////////////////////////////////////
function modify_task($task_id, $status, $mode) {
	$this->check_permissions(5);
	$this->raw_query("
	UPDATE ote_task
	SET status = '$status',
	mode = '$mode',
	admin_id = '" . $_SESSION['id'] . "',
	updated = NOW()
	WHERE id = $task_id 
	");
	return 1;
} // END of function modify_task

//////////////////////////////////////////////////////////////////////////////
function get_task_by_content( $status, $mode, $s_lang_id, $t_lang_id ) {
	return $this->query("SELECT * FROM ote_task WHERE status = '$status'
	AND mode = '$mode' AND s_lang_id = '$s_lang_id' AND t_lang_id = '$t_lang_id'");
} // end of get_task_by_content

//////////////////////////////////////////////////////////////////////////////
function do_task_list() {
	$this->show_header('Task List');
	print '<script language="JavaScript">' . "\n"
	. 'function ask(msg, url) { if (confirm(msg)) { document.location = url; } else { return; } }' . "\n"
	. '</script>';
	$limit = 10;
	if( defined('NUMBER_OF_TASKS_TO_SHOW_ON_INTRO') ) {
		$limit = constant('NUMBER_OF_TASKS_TO_SHOW_ON_INTRO');
	}

	print $this->do_task_list_table('open',
	"SELECT * FROM ote_task WHERE (
	( s_code = '" . LANG_1_CODE . "' AND t_code = '" . LANG_2_CODE. "' )
	OR
	( t_code = '" . LANG_1_CODE. "' AND s_code = '" . LANG_2_CODE . "' )
	)
	AND status = 'OPEN' 
	ORDER BY updated DESC 
	LIMIT $limit");

	print $this->do_task_list_table('closed',
	"SELECT * FROM ote_task WHERE
	(
	( s_code = '" . LANG_1_CODE . "' AND t_code = '" . LANG_2_CODE. "' )
	OR
	( t_code = '" . LANG_1_CODE. "' AND s_code = '" . LANG_2_CODE . "' )
	)
	AND status = 'CLOSED' 
	ORDER BY updated DESC 
	LIMIT $limit");

} // END function do_task_list

//////////////////////////////////////////////////////////////////////////////
function show_open() {
	$this->show_header('Open Tasks List');
	print '<script language="JavaScript">' . "\n"
	. 'function ask(msg, url) { if (confirm(msg)) { document.location = url; } else { return; } }' . "\n"
	. '</script>';
	print $this->do_task_list_table('open', "
			SELECT * 
			FROM ote_task 
			WHERE
 (
( s_code = '" . LANG_1_CODE . "' AND t_code = '" . LANG_2_CODE. "' )
	OR
( t_code = '" . LANG_1_CODE. "' AND s_code = '" . LANG_2_CODE . "' )
 )
			AND status = 'OPEN' 
			ORDER BY updated DESC ", $do_not_show_more=1);
} // END function show_open

//////////////////////////////////////////////////////////////////////////////
function show_closed() {
	$this->show_header('Closed Tasks List');
	print '<script language="JavaScript">' . "\n"
	. 'function ask(msg, url) { if (confirm(msg)) { document.location = url; } else { return; } }' . "\n"
	. '</script>';
	print $this->do_task_list_table('closed', "
			SELECT * FROM ote_task 
			WHERE
 (
( s_code = '" . LANG_1_CODE . "' AND t_code = '" . LANG_2_CODE. "' )
	OR
( t_code = '" . LANG_1_CODE. "' AND s_code = '" . LANG_2_CODE . "' )
 )
			AND status = 'CLOSED' 
			ORDER BY updated DESC ", $do_not_show_more=1);
} // END function show_closed
	
//////////////////////////////////////////////////////////////////////////////
function do_task_list_table( $type, $sql, $do_not_show_more=0 ) {
	print '<table border="0" cellpadding="3" cellspacing="1"><tr>
	<td>Admin</td>
	<td>' . $type . '</td>
	<td colspan="2">Source</td>
	<td colspan="2">Target</td>
	<td>User</td>
	<td>Start Time</td>
	<td>Last Updated</td>
	<td>Admin</td>
	</tr>';
	$r = $this->query($sql);
	if( $this->number_of_rows == 0 ) {
		print '<tr><td colspan="7">None</td></tr></table><br />&nbsp;<br />'; 
		return; 
	}
	while( $x = each($r) ) {
		$x = $x[1];
		$source_word = $x->s_word;
		$source_language_isothree = $this->get_language_code( $x->s_code );
		$target_word = $x->t_word;
		$target_language_code = $this->get_language_code( $x->t_code );
		if($bgcolor == ' bgcolor="' . $this->__('TABLE_CELL_BACKGROUND_COLOR_1') . '"' ) { 
			$bgcolor = ' bgcolor="' . $this->__('TABLE_CELL_BACKGROUND_COLOR_2') . '"'; 
		} else { 
			$bgcolor = ' bgcolor="' . $this->__('TABLE_CELL_BACKGROUND_COLOR_1'). '"'; 
		}
		print '<tr' . $bgcolor .'><td>';
		switch ( $type ) {
			case 'open':
				print '<a href="' . OTE_DIRECTORY . 'task/accept/' . $x->id . '/">Accept</a> &nbsp; &nbsp; ';
				print '<a href="JavaScript:ask('
				. "'Are you sure you want to REJECT task #" . $x->id . "?',"
				. "'" .	OTE_DIRECTORY . 'task/delete/' . $x->id . '/' . "'"
				. ")" . '">Reject</a> &nbsp; &nbsp;';
				break;
			case 'closed':
				print '<a href="JavaScript:ask('
			 . "'Are you sure you want to CLEAR task #" . $x->id . "?',"
			 . "'" . OTE_DIRECTORY . 'task/delete/' . $x->id . '/' . "'"
			 . ")" . '">Clear</a> &nbsp; &nbsp;';
			 break;
		} // end switch
		print '</td><td>' . $x->mode . '</td><td>';
		if( $source_word ) {
			print $source_language_isothree . '</td><td>';
			print '<a href="' . HOSTNAME . 'word/' . $source_language_isothree
			. '/' . urlencode($source_word) . '">';
			print $source_word;
			print '&nbsp;</td>'; 
			print '</td><td nowrap>';
			if( $target_word ) {
				print $target_language_code . '</td><td>';
				print '<a href="' . HOSTNAME . 'word/' . $target_language_code
				. '/' . urlencode($target_word) . '">';
				print $target_word;
			}
		} else { 
			print '&nbsp;</td><td>&nbsp;</td>'; 
		}
		print '<td>' . $this->get_user_by_id($x->user_id) . '</td>'
		. '<td><small>' . $x->started . '</small></td>'
		. '<td><small>' . $x->updated . '</small></td>'
		. '<td><small>' . $this->get_user_by_id($x->admin_id) . '</small></td>'
		. '</tr>';
	} // end while each result
	if( !$do_not_show_more ) {
		print '<tr><td colspan="10"><a href="'
		. HOSTNAME . 'task/' . $type . '/">View More <b>' . $type . '</b> Tasks...</a><hr size="1" /></td></tr>';
	}	
	print '</table><br />&nbsp;<br />';
} // END function do_task_list_table

} // end of class ote_task
