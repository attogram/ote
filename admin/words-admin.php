<?php
// Open Translation Engine - Words Admin v0.0.1

namespace Attogram;

$this->page_header('Words Admin - OTE 1.0.0-dev');

tabler(
  $attogram = $this,
  $table = 'word',
  $name_singular = 'word',
  $name_plural = 'words',
  $public_link = '../word/',
  $col = array(
    array('class'=>'col-md-8', 'title'=>'word', 'key'=>'word'),
    array('class'=>'col-md-3', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT word, id FROM word ORDER BY word',
  $admin_link = '../words-admin/',
  $show_edit = TRUE
);

$this->page_footer();
