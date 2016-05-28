<?php
// Additional Attogram config setup for OTE

$config['site_name'] = 'Open Translation Engine';

$config['depth']['dictionary'] = 4; // Dictionary Depth

$config['depth']['word'] = 5; // word viewer Depth

$config['depth']['export'] = 4; // Export Depth

// By default, a slash / is forced onto the end of all URLs, if not already present.
// To allow slash-less ending URLs, list the action name here:
$config['force_slash_exceptions'] = array(
  'word',
);
