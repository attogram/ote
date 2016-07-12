<?php // Open Translation Engine - Languages Admin v0.2.4

namespace Attogram;

$this->pageHeader('Languages Admin');

$this->database->tabler(
  'language', // $table
  'id', // $tableId
  'language', // $nameSingular
  'languages', // $namePlural
  '../languages/', // $publicLink
  array( // $col
    array('class'=>'col-md-7', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-2', 'title'=>'<code>code</code>', 'key'=>'code'),
    array('class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  'SELECT name, code, id FROM language ORDER BY id', // $sql
  '../languages-admin/', // $adminLink
  true, // $showEdit
  100 // $perPage
);

$this->pageFooter();
