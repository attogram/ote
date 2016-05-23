<?php
namespace Attogram;

$this->page_header('Words Admin - OTE 1.0.0-dev');

tabler(
  $attogram = $this,
  $table = 'word',
  $name_singular = 'word',
  $name_plural = 'words',
  $public_link = '../words/',
  $col = array(
    array('class'=>'col-md-7', 'title'=>'word', 'key'=>'word'),
    array('class'=>'col-md-5', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT word, id FROM word ORDER BY id',
  $admin_link = '../words-admin/',
  $admin_create = '../db-admin/?table=' . $table .'&amp;action=row_create',
  $admin_edit = '../db-admin/?table=' . $table . '&amp;action=row_editordelete&amp;type=edit&amp;pk=',
  $admin_delete = '../db-admin/?table=' . $table . '&amp;action=row_editordelete&amp;type=delete&amp;pk='
);

$this->page_footer();
