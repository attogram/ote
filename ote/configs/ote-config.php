<?php
// Open Translation Engine - Attogram config v0.0.1

namespace Attogram;

global $config;

$config['site_name'] = 'Open Translation Engine';

$config['depth']['dictionary'] = 3; // Dictionary Depth

$config['depth']['word'] = 4; // word viewer Depth

$config['depth']['export'] = 3; // Export Depth

// By default, a slash / is forced onto the end of all URLs, if not already present.
// To allow slash-less ending URLs, list the action name here:
$config['force_slash_exceptions'][] = 'word';
