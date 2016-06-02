<?php
// Open Translation Engine - Languages Page v0.0.2

namespace Attogram;

$this->page_header('Languages');

tabler(
  $attogram = $this,
  $table = 'language',
  $name_singular = 'language',
  $name_plural = 'languages',
  $public_link = '../languages/',
  $col = array(
    array('class'=>'col-md-9', 'title'=>'language', 'key'=>'language'),
    array('class'=>'col-md-3', 'title'=>'<code>code</code>', 'key'=>'code'),
  ),
  $sql = 'SELECT language, code FROM language ORDER BY id',
  $admin_link = '../languages-admin/',
  $show_edit = FALSE
);

$this->page_footer();
