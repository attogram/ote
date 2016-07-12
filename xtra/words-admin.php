<?php // Open Translation Engine - Words Admin v0.2.3

namespace Attogram;

$this->pageHeader('Words Admin');

$this->database->tabler(
  $table = 'word',
  $table_id = 'id',
  $name_singular = 'word',
  $name_plural = 'words',
  $public_link = '../word/',
  $col = array(
    array('class'=>'col-md-8', 'title'=>'word', 'key'=>'word'),
    array('class'=>'col-md-3', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT word, id FROM word ORDER BY word',
  $admin_link = '../words-admin/',
  $show_edit = true,
  $per_page = 1000
);

$this->pageFooter();
