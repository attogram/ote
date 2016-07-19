<?php // Open Translation Engine - Tags Admin v0.2.5

namespace Attogram;

$this->pageHeader('Tags Admin');

$this->database->tabler(
  'tag', // $table
  'id', // $tableId
  'tag', // $nameSingular
  'tags', // $namePlural
  '../tags/', // $publicLink
  array(// $col
    array('class'=>'col-md-7', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-2', 'title'=>'code', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  'SELECT id, code, name FROM tag ORDER BY name', // $sql
  '../tags-admin/', // $adminLink
  true, // $showEdit
  100 // $perPage
);

$this->pageFooter();
