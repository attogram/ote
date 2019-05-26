<?php
// Open Translation Engine - Attogram config v0.0.9

namespace Attogram;

global $config;

$config['siteName'] = 'Open Translation Engine';

$config['depth']['dictionary'] = 3; // Dictionary Depth

$config['depth']['word'] = 4; // word viewer Depth
$config['noEndSlash'][] = 'word'; // no end slashes on word

$config['depth']['export'] = 3; // Export Depth
