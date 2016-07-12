<?php // Open Translation Engine - Tags Admin v0.2.3

namespace Attogram;

$this->pageHeader('Tags Admin');

$this->database->tabler(
  $table = 'tag',
  $tableId = 'id',
  $nameSingular = 'tag',
  $namePlural = 'tags',
  $publicLink = '../tags/',
  $col = array(
    array('class'=>'col-md-7', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-2', 'title'=>'code', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT id, code, name FROM tag ORDER BY name',
  $adminLink = '../tags-admin/',
  $showEdit = true,
  $perPage = 100
);

$this->pageFooter();
