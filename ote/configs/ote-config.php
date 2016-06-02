<?php
// Open Translation Engine - Attogram config v0.0.1

namespace Attogram;

global $config;

$config['site_name'] = 'Open Translation Engine';

$config['depth']['dictionary'] = 3; // Dictionary Depth

$config['depth']['word'] = 4; // word viewer Depth
$config['force_slash_exceptions'][] = 'word'; // no end slashes on word

$config['depth']['export'] = 3; // Export Depth
