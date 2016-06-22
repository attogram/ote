<?php // Open Translation Engine - Slush Pile Page v0.0.1

namespace Attogram;

$this->page_header('Slush Pile');

print '<div class="container"><h1>Slush Pile</h1>';

$slush = $this->db->query('SELECT * FROM slush_pile ORDER BY id DESC');

foreach( $slush as $s )
print '<pre>' . print_r($s,1) . '</pre>';


print '</div>';
