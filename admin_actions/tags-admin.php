<?php // Open Translation Engine - Tags Admin v0.2.0

namespace Attogram;

$this->page_header('Tags Admin');

$this->db->tabler(
  $table = 'tag',
  $table_id = 'id',
  $name_singular = 'tag',
  $name_plural = 'tags',
  $public_link = '../tags/',
  $col = array(
    array('class'=>'col-md-7', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-2', 'title'=>'code', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT id, code, name FROM tag ORDER BY name',
  $admin_link = '../tags-admin/',
  $show_edit = true,
  $per_page = 100
);

$this->page_footer();
