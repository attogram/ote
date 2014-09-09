<?php
// Open Translation Engine 
// TEMPLATE SETTINGS


// System Settings 
// These can be defined here, for this specific template
// Or set globally for all templates in the ../settings.php file

// Uncomment to Force all words to be lowercase
define('FORCE_LOWERCASE', '1');

// Uncomment to allow Anonymous users (not logged in) to submit suggestions
//define('ALLOW_ANONYMOUS_GUESTS_TO_SUGGEST', '1'); 

// Uncomment to override the generic Guest Alert
//define('GUEST_ALERT', 'Hello Anonymous Guest! Please contribute to the dictionary!'); 

// Uncomment to override how many words to show per page on /list
//define('NUMBER_OF_WORDS_TO_SHOW_ON_LIST', 500); 

// Uncomment to override how many tasks to show for Open and Closed
//define('NUMBER_OF_TASKS_TO_SHOW_ON_INTRO', 10);  in /task intro menu




// Alternating Table Cell Background Colors - for /task and /translate
$text['TABLE_CELL_BACKGROUND_COLOR_1'] = '#F9F9F9'; 
$text['TABLE_CELL_BACKGROUND_COLOR_2'] = '#FFFFFF';


// Template Text: General
$text['open translation engine'] = 'Open Translation Engine';
$text['welcome to the open translation engine'] = 'Welcome to the Open Translation Engine';
$text['add new word'] = 'add new word';

$text['none'] = 'none';
$text['random word'] = 'random word';
$text['source'] = 'Source';
$text['target'] = 'target';
$text['translate'] = 'translate';
$text['word pair'] = 'word pair';
$text['word pairs'] = 'Word Pairs';
$text['sugggest a new translation'] = 'Sugggest a new translation';
$text['does this translation need improvement?'] = 'Does this translation need improvement?';
$text['login to the open translation engine to suggest edits for this word'] = 'Login to the Open Translation Engine to suggest edits for this word';

// Template Text: List
$text['translation dictionaries'] = 'Translation Dictionaries';
$text['dictionary'] = 'Dictionary';
$text['next page'] = 'Next Page';
$text['previous page'] = 'Previous Page';
$text['no words found'] = 'No Words Found';

// Template Text: Random
$text['random translation'] = 'Random Translation';
$text['new random translation'] = 'New Random Translation';
$text['permalink for word'] = 'Permalink For Word: ';

// Template Text: User Access
$text['you are now registered and logged in'] = 'you are now registered and logged in';
$text['you last logged in at:'] = 'you last logged in at:';
$text['login'] = 'login';
$text['logout'] = 'logout';
$text['username'] = 'username';
$text['password'] = 'password';
$text['email'] = 'email';
$text['register'] = 'register';
$text['hello'] = 'hello';
$text['incorrect login'] = 'incorrect login';
$text['2nd password contains invalid characters'] = '2nd password contains invalid characters';
$text['Please enter your username and password'] = 'Please enter your username <b>and</b> password';
$text['email contains invalid characters'] = 'email contains invalid characters';
$text['please enter a password'] = 'please enter a password';
$text['please enter a username'] = 'please enter a username';
$text['please enter a word'] = 'please enter a word';
$text['please enter your password again to confirm'] = 'please enter your password again to confirm';
$text['sorry, that username is already taken'] = 'sorry, that username is already taken';
$text['username contains invalid characters'] = 'username contains invalid characters';
$text['password contains invalid characters'] = 'password contains invalid characters';
$text['passwords do not match'] = 'passwords do not match';

// Template Text: Import/Export
$text['import'] = 'Import';
$text['export'] = 'Export';
$text['end of import'] = '<p>End of Import</p>';
$text['<space>'] = '<SPACE>';
$text['<tab>'] = '<TAB>';
$text['gmt'] = 'GMT';
$text['testing'] = 'testing: ';
$text['size'] = 'size';
$text['delimiter'] = 'Delimiter';
$text['for tab character use: \t'] = 'for tab character use: <code>\t</code>';
//$text['ok word'] = 'ok word';
//$text['add word'] = 'add word';
//$text['ok link'] = 'ok link';
//$text['add link'] = 'add link';
$text['ignored: malformed or comment'] = '*** ignored: malformed or comment';
$text['error: please select a language pair'] = 'ERROR: please select a language pair';
$text['error: please select a format'] = 'ERROR: please select a format';
$text['error: text format exports require a delimiter. please select a delimter'] = 'ERROR: text format exports require a delimiter.  Please select a delimter';
$text['please select a language pair'] = 'Please select a language pair:';

// Template Text: Tasks
$text['are you sure you want to clear task #'] = 'are you sure you want to clear task #';
$text['are you sure you want to delete the link:'] = 'are you sure you want to delete the link:';
$text['are you sure you want to reject task #'] = 'are you sure you want to reject task #';
$text['closed tasks list'] = 'closed tasks list';
$text['closed'] = 'closed';
$text['open tasks list'] = 'open tasks list';
$text['open'] = 'open';
$text['created'] = 'created';
$text['delete link'] = 'delete link';
$text['delete'] = 'delete';
$text['accept'] = 'accept';
$text['view more tasks'] = 'view more tasks';
$text['task list'] = 'task list';
$text['tasks'] = 'tasks';
$text['start time'] = 'start time';
$text['reject'] = 'reject';
$text['suggestion saved'] = 'suggestion saved';
$text['view more'] = 'view more';
$text['task error'] = 'task error';
$text['task permission denied'] = 'task permission denied';
$text['error: no source word or invalid source word'] = 'error: no source word or invalid source word';
$text['error: no target word or invalid target word'] = 'error: no target word or invalid target word';
$text['error - no source language id'] = 'error - no source language id';
$text['error - no target language id'] = 'error - no target language id';
$text['error: you do not have permission to add or suggest edits'] = 'error: you do not have permission to add or suggest edits';
$text['error: you do not have permission to delete or suggest deletes'] = 'error: you do not have permission to delete or suggest deletes';

// Template Text: Admin: User
$text['user admin'] = 'user admin';
$text['guest'] = 'guest';
$text['guest: view only'] = 'guest: view only';
$text['user'] = 'user';
$text['user: suggest dictionary edits'] = 'user: suggest dictionary edits';
$text['dictionary admin'] = 'dictionary admin';
$text['dictionary admin: edit dictionary'] = 'dictionary admin: edit dictionary';
$text['site admin'] = 'site admin';
$text['site admin: edit users'] = 'site admin: edit users';
$text['last login'] = 'last login';
$text['last updated'] = 'last updated';
$text['level'] = 'level';
$text['admin users'] = 'admin users';
$text['users'] = 'users';
$text['modify'] = 'modify';
$text['demo mode - you may not edit this user'] = 'demo mode - you may not edit this user';
$text['user updated'] = 'User Updated';
$text['no update'] = 'No update';

// Template Text: Admin
$text['admin'] = 'admin';
$text['dictionary is clean'] = 'dictionary is clean';
$text['finding unpaired words'] = 'finding unpaired words';
$text['unpaired word dictionary cleanup'] = 'unpaired word dictionary cleanup';
$text['unpaired words deleted'] = 'unpaired words deleted';
$text['admin error: permission level 9 required'] = 'admin error: permission level 9 (Site Admin) required';
$text['admin error: permission level 5 required'] = 'admin error: permission level 5 (Dictionary Admin) required.';

// Template Text: Errors
$text['404 - language code not found'] = '404 - language code not found';
$text['404 - language not found'] = '404 - language not found';
$text['404 - word not found'] = '404 - word not found';
$text['error with input'] = 'error with input';
$text['error'] = 'error';
$text['error: can not load template'] = 'error: can not load template';
$text['error: no input'] = 'error: no input';
$text['error: no template list found from settings.php'] = 'error: no template list found from settings.php';
$text['error: settings.php not found'] = 'error: settings.php not found';
$text['error: template settings file not found'] = 'error: template settings file not found';
$text['install error check'] = 'install error check';
$text['mysql error'] = 'mysql error';
$text['no unpaired words found'] = 'no unpaired words found';
$text['ote database error'] = 'ote database error';
$text['error: no words found'] = 'ERROR: no words found';


// Template Text: Translation tool
$text['HEADER_TRANSLATION_TOOL'] = 'Translation Help:';
$text['add'] = 'Add:<br /><br />';


//
$text['available feeds'] = 'Available Feeds';
$text['modify word'] = 'Modify Word';
$text['word modified'] = 'Word Modified.';

$text['documentation'] = 'Documentation';

