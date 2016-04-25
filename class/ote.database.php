<?php /*

Open Translation Engine

Database class

*/ 
class ote_database extends ote_debug {

var $database_link; // resource locator for database (returned from mysql_connect)

//////////////////////////////////////////////////////////////////////////////
function connect() { // Open a connection to our database(s)
	if( $this->database_link ) { 
		return 1; 
	}
	$this->database_link = @mysql_connect(DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD);
	if ( $this->database_link ) {
		if( !mysql_select_db(DATABASE_NAME) ) { // connect to the specific database
			$this->no_database(); 
		}
		return 1;
	} else {
		$this->no_database(); 
	} // end check if database_link is active
} // end function connect

//////////////////////////////////////////////////////////////////////////////
function no_database() {
// http://dev.mysql.com/doc/refman/5.1/en/error-messages-client.html
	print '<pre>
OTE DATABASE ERROR 

mysql error: <font color="red">' 
. mysql_errno() . ': ' . mysql_error() . '</font>  

error dump: 
';
	$this->error_dump();
	$do_not_include_settings_file = 1;
	include('error.check.php');
	exit;
}

//////////////////////////////////////////////////////////////////////////////
function error_dump() {
	if( !defined('DEPRECIATED_V4') ) {
		print debug_print_backtrace();
	} else {
		$x = debug_backtrace();
		while( list(,$y) = each($x) ) {
			if( $y['function'] == 'error_dump' ) { continue; }
			print ''
			. '<br />file: '
			. $y['file']
			. '<br />line: '
			. $y['line']
			. '<br />function: '
			. $y['function'] 
			. '<br />'
			;

			}
	} // end if defined depreciated v4
} // end of function error_dump

//////////////////////////////////////////////////////////////////////////////
function raw_query( $sql ) {
	if( !$this->database_link ) {
		$this->connect(); 
	}
	@mysql_query( $sql );
	if( mysql_error() ) {
		$this->no_database();
	}
	return;
} // END function raw_query

//////////////////////////////////////////////////////////////////////////////
function query( $sql ) { // Generic function to do a SQL call
	if( !$this->database_link ) {
		$this->connect();
	}
	$raw = @mysql_query( $sql );
	$this->number_of_rows = @mysql_num_rows($raw);
	if( mysql_error() ) {
		$this->no_database();
	}
	$rval = array();
	$i = 0;
	while ( $row = mysql_fetch_object($raw) ) { // get one result row at a time, as an object
		$rval[++$i] = $row; // add this results object to the return value
	}
	return $rval; // return an array of result objects
} // END function query()

} // end of class ote_database