<?php // Open Translation Engine - Languages Admin v0.2.3

namespace Attogram;

$this->pageHeader('Languages Admin');

$this->database->tabler(
  $table = 'language',
  $tableId = 'id',
  $nameSingular = 'language',
  $namePlural = 'languages',
  $publicLink = '../languages/',
  $col = array(
    array('class'=>'col-md-7', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-2', 'title'=>'<code>code</code>', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT name, code, id FROM language ORDER BY id',
  $adminLink = '../languages-admin/',
  $showEdit = true,
  $perPage = 100
);

$this->pageFooter();
