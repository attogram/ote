<?php
// Open Translation Engine
// Main Object Class Definition File

define('VERSION', '0.9.8.9');

//////////////////////////////////////////////////////////////////////////////
require 'class/ote.debug.php';
require 'class/ote.database.php';
require 'class/ote.util.php';
require 'class/ote.translate.php';
require 'class/ote.template.php';
require 'class/ote.task.php';

//////////////////////////////////////////////////////////////////////////////
class ote extends ote_task {

	//////////////////////////////////////////////////////////////////////////////
	function ote() { // class constructor

		ini_set('use_trans_sid', false); // no PHPSESSID= in url
		ini_set('register_globals', false); // no automagic variables from GET/POST

		if( extension_loaded('zlib') ){
			ob_start('ob_gzhandler'); // compress output to browser, if possible
		}

		session_start();

		$system_level = '';
		if( defined('SYSTEM_LEVEL') ) {
			$system_level = constant('SYSTEM_LEVEL');
		}
		$file = $system_level . 'settings.php';
		if ( file_exists($file) ) {
			require_once( $file );
		} else { 
			$alert = 'ERROR: settings.php not found'
			. '<br />Please copy the file <b>settings.php-dist</b> to <b>settings.php</b>'
			. '<br />and enter the correct values for your system.';
			include('error.check.php');
			exit;
		} // end if file does not exist

		define('HOSTNAME', PROTOCOL . HOST . OTE_DIRECTORY);
		define('DEPTH', (sizeof( explode('/', OTE_DIRECTORY) ) - 2) );
		reset($template_list);
		$this->template_list = $template_list;
		if( $_SESSION['template_id'] && is_numeric($_SESSION['template_id']) ) {
			// do nothing
		} else {
			$_SESSION['template_id'] = 1;
		}
		if( $_GET['view'] && is_numeric($_GET['view']) ) { 
			$_SESSION['template_id'] = $_GET['view']; 
			$redir = $_SERVER["HTTP_REFERER"];
			if( !$redir || $redir == '' ) { $redir = HOSTNAME; }
			header('Location: ' . $redir);
			exit;
		} // end if view change

		define('LANG_1_NAME', $this->template_list[ $_SESSION['template_id'] ]['lang_1_name'] );
		define('LANG_1_CODE', $this->template_list[ $_SESSION['template_id'] ]['lang_1_code'] );
		define('LANG_2_NAME', $this->template_list[ $_SESSION['template_id'] ]['lang_2_name'] );
		define('LANG_2_CODE', $this->template_list[ $_SESSION['template_id'] ]['lang_2_code'] );

		// set the template directory
		$template_directory = $this->template_list[ $_SESSION['template_id'] ]['template'];

		// if the templates directory is not defined, then use the FIRST templates directory 
		if( $template_directory == '' ) {
			$template_directory = $template_list[1]['template'];
		}
		define('TEMPLATE_DIRECTORY', $template_directory);

		$template_settings_file = $system_level . 'templates/' . $template_directory . '/template.settings.php';

		if( !file_exists( $template_settings_file ) ) {
			$alert = 'Template Settings File Not Found'
			. '<br />Missing: "<b>' . $template_settings_file . '</b>"'
			. '<br />template_directory: ' . $template_directory
			. '<br />template ID: ' . $_SESSION['template_id']
			. '<br />DEBUG: if template ID is greater then number of defined templates, clear SESSION data to fix'
			. '<br />Please correct this error to install OTE.';
			$do_not_include_settings_file = 1;
			include('error.check.php');
			exit;
		}
		require_once( $template_settings_file );

		$this->text = $text; // template text messages

		if( !defined('DEPRECIATED_V4') ) {
			mb_internal_encoding('UTF-8');
			mb_http_output('UTF-8');
		}
		

	} // end class constructor ote

} // end of class OTE

//////////////////////////////////////////////////////////////////////////////
$ote = new ote(); // Create the OTE object
$ote->connect(); // connect to the OTE database
