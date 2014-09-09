<html><head><title>Error Check - Open Translation Engine</title></head><body>
<p>Error Check - Open Translation Engine</p>
<hr size="1" /><?php



$start_passed = ' <font color="green"><b>';
$start_failed = ' <font color="red"><b>';
$end_passed = '</b></font> ';
$end_failed = '</b></font><br />';
$passed = $start_passed . 'PASSED' . $end_passed;
$failed = $start_failed . 'FAILED' . $end_failed;
$warning = $start_failed . 'WARNING' . $end_passed;

if( !isset($do_not_include_settings_file) ) { $do_not_include_settings_file = 0; }


if( $alert ) {
  print '<p style="background-color:yellow; color:black;">' . $alert . '</p><hr size="1" />';
} 




    print '<br />Settings Test: ' . $_SERVER['PHP_SELF'];

    print '<br />';
    print '<br />pre-check 1 ... ';
	if( function_exists('error_get_last') ) { 
		print $passed; 
		$use_error_get_last = 1; 
	} else { 
		print $failed; 
		$use_error_get_last = 0;
	}

    print '<br />pre-check 2 ...';


    if( $use_error_get_last ) {
      @trigger_error('OTE_TEST');
      $error = error_get_last();
      if( $error['message'] != 'OTE_TEST' ) {
        print $failed; 
        print_r($error); 
      } else {
        print $passed;
      }
      @trigger_error('OTE_TEST');
    } else {
      print " DEPRECIATED - FAILED ";
    }
    
    print '<br />Checking for <b>ote.class.php</b> ... ';
    if( file_exists('ote.class.php') ) { print $passed; } else { print $failed; }

    if(  $do_not_include_settings_file != 1 ) {
      print '<br />Checking for <b>settings.php</b> ... ';
      if( file_exists('settings.php') ) { print $passed; } else { print $failed; }

      print '<br />Including <b>settings.php</b> ... ';
      @include('settings.php');
      if( $use_error_get_last ) {
        $error = error_get_last();
        if( $error['message'] != 'OTE_TEST' ) {
          print $failed . '<br /><br />' . $start_failed . $error['message'] . $end_failed; 
        } else {
          print $passed;
        }
        @trigger_error('OTE_TEST');
      } else { print ' DEPRECIATED FAILED '; }

    } // end if  $do_not_include_settings_file
    else { print '<br />Settings file already included'; }



    print '<br />';
    $hostname = PROTOCOL . HOST . OTE_DIRECTORY;
    print '<br />HOSTNAME manual check: <a href="' . $hostname . '">' . $hostname . '</a>';

    print '<br />Filesystem settings check: <b>PROTOCOL</b> ... ';
    if( defined('PROTOCOL') ) { print $passed; } else { print $failed; }

    print '<br />Filesystem settings check: <b>HOST</b> ... ';
    if( defined('HOST') ) { print $passed; } else { print $failed; }

    print '<br />Filesystem settings check: <b>OTE_DIRECTORY</b> ... ';
    if( defined('OTE_DIRECTORY') ) { print $passed; } else { print $failed; }






    print '<br />';
    print '<br />Database settings existance: <b>DATABASE_SERVER</b> ... ';
    if( defined('DATABASE_SERVER') ) { print $passed . constant('DATABASE_SERVER'); } else { print $failed; }

    print '<br />Database settings existance: <b>DATABASE_NAME</b> ... ';
    if( defined('DATABASE_NAME') ) { print $passed . constant('DATABASE_NAME'); } else { print $failed; }

    print '<br />Database settings existance: <b>DATABASE_USER</b> ... ';
    if( defined('DATABASE_USER') ) { print $passed . constant('DATABASE_USER'); } else { print $failed; }

    print '<br />Database settings existance: <b>DATABASE_PASSWORD</b> ... ';
    if( defined('DATABASE_PASSWORD') ) { print $passed . ' (not shown)'; } else { print $failed; }

    print '<br />Connecting to database server ... ';
    $database_link = @mysql_connect(DATABASE_SERVER, DATABASE_USER, DATABASE_PASSWORD);
    if ( $database_link ) { 
      print $passed; 
    } else {
      print $failed . mysql_error();
    }

    print '<br />Selecting database ... ';
    $database_select = @mysql_select_db(DATABASE_NAME);
    if ( $database_select ) { 
      print $passed; 
    } else {
      print $failed . mysql_error();
    } 

    print '<br />Database table: ote_word ... ';
    @mysql_query('SELECT count(id) FROM ote_word');
    if( $err = mysql_error() ) { print $failed . $err; } else { print $passed; }

    print '<br />Database table: ote_word2word ... ';
    @mysql_query('SELECT count(id) FROM ote_word2word');
    if( $err = mysql_error() ) { print $failed . $err; } else { print $passed; }

    print '<br />Database table: ote_user ... ';
    @mysql_query('SELECT count(id) FROM ote_user');
    if( $err = mysql_error() ) { print $failed . $err; } else { print $passed; }

    print '<br />Database table: ote_task ... ';
    @mysql_query('SELECT count(id) FROM ote_task');
    if( $err = mysql_error() ) { print $failed . $err; } else { print $passed; }





    print '<br />';
    print '<br />Template List existance check:  ... ';
    if( isset($template_list) ) { print $passed; } else { print $failed; }

    print '<br />Template List At Least 1 view defined check:  ... ';
    if( sizeof($template_list) > 0 ) { print $passed; } else { print $failed; }

    @reset($template_list);
    $count = 0;
    while( list($trash,$x) = @each($template_list) ) {
      $count++;
      print '<br /><br />Template # ' . $count . ' name: ';
      if( isset( $x['name'] ) ) { print htmlentities( $x['name'] ) . $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' template: ';
      if( isset( $x['template'] ) ) { print $x['template'] . $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' settings: ';
      if( isset( $x['settings'] ) ) { print $x['settings'] . $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' settings: checking existance: ';
      if( file_exists( $x['settings'] ) ) { print $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' lang_1_name: ';
      if( isset( $x['lang_1_name'] ) ) { print $x['lang_1_name'] . $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' lang_1_code: ';
      if( isset( $x['lang_1_code'] ) ) { print $x['lang_1_code'] . $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' lang_2_name: ';
      if( isset( $x['lang_2_name'] ) ) { print $x['lang_2_name'] . $passed; } else { print $failed; }

      print '<br />Template # ' . $count . ' lang_2_code: ';
      if( isset( $x['lang_2_code'] ) ) { print $x['lang_2_code'] . $passed; } else { print $failed; }

    } // end while


    print '<br /><br />Clean check: <b>CLEAN_PUNCTUATION</b> ... ';
    if( defined('CLEAN_PUNCTUATION') ) { print $passed 
    . htmlentities( constant('CLEAN_PUNCTUATION') ); } else { print $warning 
	. ' No punctuation cleaning'; }




	print '<br /><br />Multibyte strings: <b>DEPRECIATED_V4</b> ... ';
	if( defined('DEPRECIATED_V4') ) { 
		print ' Defined. Excluding Multibyte strings';
	} else { 
		print ' Not Defined. Including Multibyte strings:';
		print '<br />Multibyte strings: mb_ereg_replace ... ';
		if( function_exists('mb_ereg_replace') ) { print $passed; } else { print $failed; }
		print '<br />Multibyte strings: mb_eregi_replace ... ';
		if( function_exists('mb_eregi_replace') ) { print $passed; } else { print $failed; }
		print '<br />Multibyte strings: mb_strtolower ... ';
		if( function_exists('mb_strtolower') ) { print $passed; } else { print $failed; }
		print '<br />Multibyte strings: mb_internal_encoding ... ';
		if( function_exists('mb_internal_encoding') ) { print $passed; } else { print $failed; }
		print '<br />Multibyte strings: mb_http_output ... ';
		if( function_exists('mb_http_output') ) { print $passed; } else { print $failed; }
	} // end if defined DEPRECIATED_V4








?>
<hr size="1" />
<p>End of error check</p>
</body></html>