<?php
// docs/index.php

define('SYSTEM_LEVEL','../');
require_once( SYSTEM_LEVEL . 'ote.class.php');
$ote->show_header( $ote->__('documentation') );
print $ote->get_template('docs.html');
$ote->show_footer(); 
