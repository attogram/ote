<?php // Open Translation Engine - Tags Page v0.1.0

namespace Attogram;

$this->page_header('Tags');

print '<div class="container"><h1>⛓ Tags</h1></div>';

$this->db->tabler(
  $table = 'tag',
  $name_singular = 'tag',
  $name_plural = 'tags',
  $public_link = '../tags/',
  $col = array(
    array('class'=>'col-md-6', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-6', 'title'=>'code', 'key'=>'code'),
  ),
  $sql = 'SELECT id, code, name FROM tag ORDER BY name',
  $admin_link = '../tags-admin/',
  $show_edit = false
);

$this->page_footer();
