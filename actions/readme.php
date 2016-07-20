<?php
// Open Translation Engine - README page v0.0.4

namespace Attogram;

$this->pageHeader('README');
print '<div class="container">';

$content = '';

$f = __DIR__ . '/../README.md';
if (!is_readable($f)) {
    $this->log->error('readme.php: file not found: ' . $f);
} else {
    $content .= $this->getMarkdown($f);
}

$f = $this->attogramDirectory . 'README.md';
if (!is_readable($f)) {
    $this->log->error('readme.php: file not found: ' . $f);
} else {
    $content .= '<br /><br /><hr /><em>Attogram Framework README:</em>';
    $content .= $this->getMarkdown($f);
}

print $content . '</div>';

print $this->pageFooter();
