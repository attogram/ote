<?php
namespace Attogram;

$this->page_header('Words - OTE 1.0.0-dev');

tabler(
  $attogram = $this,
  $table = 'language',
  $name_singular = 'language',
  $name_plural = 'languages',
  $public_link = '../languages/',
  $col = array(
    array('class'=>'col-md-7', 'title'=>'language', 'key'=>'language'),
    array('class'=>'col-md-2', 'title'=>'<code>code</code>', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT language, code, id FROM language ORDER BY id',
  $admin_link = '../languages-admin/',
  $admin_create = '../db-admin/?table=' . $table .'&amp;action=row_create',
  $admin_edit = '../db-admin/?table=' . $table . '&amp;action=row_editordelete&amp;type=edit&amp;pk=',
  $admin_delete = '../db-admin/?table=' . $table . '&amp;action=row_editordelete&amp;type=delete&amp;pk='
);

$this->page_footer();
