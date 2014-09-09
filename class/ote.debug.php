<?php /*

Open Translation Engine

Debug class

*/ 

class ote_debug {

function xdebug($msg) {
	ob_start();
	print_r($msg);
	$gc = ob_get_contents();
	ob_end_clean();
	return $gc;
}

} // end of class ote_debug