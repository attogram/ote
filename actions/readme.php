<?php // Open Translation Engine - README page v0.0.2

namespace attogram;

$this->page_header('README');
print '<div class="container">';

$content = '';

$f = __DIR__ . '/../README.md';
if( !is_readable($f) ) {
  $this->log->error( 'readme.php: file not found: ' . $f );
} else {
  $content .= $this->get_markdown( $f );
}

$f = $this->attogramDirectory . 'README.md';
if( !is_readable($f) ) {
  $this->log->error('readme.php: file not found: ' . $f );
} else {
  $content .= '<br /><br /><hr /><em>Attogram Framework README:</em>';
  $content .= $this->get_markdown( $f );
}

print $content . '</div>';

print $this->page_footer();
