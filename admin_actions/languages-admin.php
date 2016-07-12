<?php // Open Translation Engine - Languages Admin v0.2.3

namespace attogram;

$this->pageHeader('Languages Admin');

$this->database->tabler(
  $table = 'language',
  $table_id = 'id',
  $name_singular = 'language',
  $name_plural = 'languages',
  $public_link = '../languages/',
  $col = array(
    array('class'=>'col-md-7', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-2', 'title'=>'<code>code</code>', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT name, code, id FROM language ORDER BY id',
  $admin_link = '../languages-admin/',
  $show_edit = true,
  $per_page = 100
);

$this->pageFooter();
