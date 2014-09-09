<?php 
//  Open Translation Engine
//  Example Settings File
//  Copy this file to 'settings.php' and enter the correct settings below:


define('DATABASE_SERVER',   'localhost');   // Hostname of database server: 'localhost', 'mysql.example.com'
define('DATABASE_NAME',     'OTE');     // Name of database: 'ote'
define('DATABASE_USER',     'root');     // access database via this username
define('DATABASE_PASSWORD', ''); // access database via this password

define('PROTOCOL',      'http://');   // web access protocol:  'http://', 'https://'  
define('HOST',          'localhost'); // name of web host:  'localhost', 'example.com'
define('OTE_DIRECTORY', '/ote/stable/');     // Relative Directory of installation. 
                                      // Use '/' for top level
                                      // for non-top level, include preceding AND trailing slash     

// Template Settings
$i = 0; // do not edit this line

// Default Template
// You must have at least this FIRST template defined correctly
$i++;
$template_list[$i]['name']        = 'English = Whatever'; // display name of this template, used in template selector
$template_list[$i]['template']    = 'eng.english'; // Name of the directory for this template (within /template/ directory)
$template_list[$i]['settings']    = 'templates/eng.english/template.settings.php'; // Name of the template settings file
$template_list[$i]['lang_1_name'] = 'English'; // Language 1 Name (must be URL safe, no spaces)
$template_list[$i]['lang_1_code'] = 'eng'; // Language 1 Code (must be 3 letters, and URL safe) (ISO 639)
$template_list[$i]['lang_2_name'] = 'Whatever'; // Language 2 Name
$template_list[$i]['lang_2_code'] = 'art'; // Language 2 Code

/*
// START Sample Templates:

$i++;
$template_list[$i]['name']        = 'English = Dutch';
$template_list[$i]['template']    = 'eng.english';
$template_list[$i]['settings']    = 'templates/eng.english/template.settings.php'; 
$template_list[$i]['lang_1_name'] = 'English';
$template_list[$i]['lang_1_code'] = 'eng';
$template_list[$i]['lang_2_name'] = 'Dutch';
$template_list[$i]['lang_2_code'] = 'nld';

$i++;
$template_list[$i]['name']        = 'English = Indonesian';
$template_list[$i]['template']    = 'eng.english';
$template_list[$i]['settings']    = 'templates/eng.english/template.settings.php'; 
$template_list[$i]['lang_1_name'] = 'English';
$template_list[$i]['lang_1_code'] = 'eng';
$template_list[$i]['lang_2_name'] = 'Indonesian';
$template_list[$i]['lang_2_code'] = 'ind';

$i++;
$template_list[$i]['name']        = 'English = Toba Batak';
$template_list[$i]['template']    = 'eng.english';
$template_list[$i]['settings']    = 'templates/eng.english/template.settings.php'; 
$template_list[$i]['lang_1_name'] = 'English';
$template_list[$i]['lang_1_code'] = 'eng';
$template_list[$i]['lang_2_name'] = 'Batak';
$template_list[$i]['lang_2_code'] = 'bbc';

$i++;
$template_list[$i]['name']        = 'Hello in many languages';
$template_list[$i]['template']    = 'eng.english';
$template_list[$i]['settings']    = 'templates/eng.english/template.settings.php'; 
$template_list[$i]['lang_1_name'] = 'English';
$template_list[$i]['lang_1_code'] = 'eng';
$template_list[$i]['lang_2_name'] = 'Hello';
$template_list[$i]['lang_2_code'] = 'qqq';

// END Sample Templates
*/



// START System Settings 

// These can be defined within each template.settings.php files  
// Or set globally for all templates here (the settings.php file):

// Uncomment to allow Anonymous users to submit suggestions (for all templates)
//define('ALLOW_ANONYMOUS_GUESTS_TO_SUGGEST', '1'); 

// Punctuation characters to remove from /translate tool, and others
define('CLEAN_PUNCTUATION', '.,?!:;<>=+_()*&^%$#@~`"' . "'" );

// Uncomment to Force all words to be lowercase
//define('FORCE_LOWERCASE', '1');

//Define the encryption salt.
 define ('SALT', 'sdfhj!*23qjhqi723q8dssfk');

// END System Settings 



// START Installation Settings

// Uncomment for Demo Mode -- Set to USER ID of the user you want to protect from changes 
//define('DEMO_MODE', '1'); 

// Uncomment for installs on depreciated MySQL 4/PHP 4 systems
//define('DEPRECIATED_V4', '1');

// END Installation Settings
