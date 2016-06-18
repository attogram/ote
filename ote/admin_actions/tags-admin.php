<?php // Open Translation Engine - Tags Admin v0.1.0

namespace Attogram;

$this->page_header('Tags Admin');

$this->db->tabler(
  $attogram = $this,
  $table = 'tag',
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
  $show_edit = true
);

$this->page_footer();
