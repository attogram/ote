<?php
// Open Translation Engine - Languages Admin v0.0.3

namespace Attogram;

$this->page_header('Languages Admin');

tabler(
  $attogram = $this,
  $table = 'language',
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
  $show_edit = TRUE
);

$this->page_footer();
