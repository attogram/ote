<?php
// Attogram config

namespace Attogram;
$config = array();

// Uncomment to change default values...

//$config['attogram_directory'] = '../';  // The Attogram installation directory, with trailing slash

//$config['debug'] = FALSE; // Debug/logging - TRUE or FALSE

$config['site_name'] = 'Open Translation Engine';

//$config['admins'] = array( '127.0.0.1', '::1', );

//$config['depth']['*'] = 2; // Default Depth
//$config['depth'][''] = 1; // Homepage Depth

$config['depth']['dictionary'] = 4; // Dictionary Depth
$config['depth']['w'] = 5; // word viewer Depth
