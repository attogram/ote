<?php // Open Translation Engine - Tags Page v0.2.4

namespace Attogram;

$this->pageHeader('Tags');

print '<div class="container"><h1 class="squished">⛓ Tags</h1></div>';

$this->database->tabler(
  'tag', // $table
  'id', // $tableId
  'tag', // $nameSingular
  'tags', // $namePlural
  '../tags/', // $publicLink
  array( // $col
    array('class'=>'col-md-6', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-6', 'title'=>'code', 'key'=>'code'),
  ),
  'SELECT id, code, name FROM tag ORDER BY name', // $sql
  '../tags-admin/', // $adminLink
  false, // $showEdit
  100 // $perPage
);

$this->pageFooter();
