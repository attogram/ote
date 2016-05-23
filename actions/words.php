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
    array('class'=>'col-md-12', 'title'=>'word', 'key'=>'word'),
  ),
  $sql = 'SELECT word FROM word ORDER BY id',
  $admin_link = '../words-admin/',
  $show_edit = FALSE
);

$this->page_footer();
