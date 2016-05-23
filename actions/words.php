<?php
namespace Attogram;

$this->page_header('Words - OTE 1.0.0-dev');

tabler(
  $attogram = $this,
  $table = 'word',
  $name_singular = 'word',
  $name_plural = 'words',
  $public_link = '../words/',
  $col = array(
    array('class'=>'col-md-8', 'title'=>'word', 'key'=>'word'),
    array('class'=>'col-md-4', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT word, id FROM word ORDER BY id',
  $admin_link = '../words-admin/',
  $admin_create = FALSE,
  $admin_edit = FALSE,
  $admin_delete = FALSE
);

$this->page_footer();
