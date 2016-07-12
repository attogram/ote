<?php // Open Translation Engine - Tags Page v0.2.3

namespace Attogram;

$this->pageHeader('Tags');

print '<div class="container"><h1 class="squished">⛓ Tags</h1></div>';

$this->database->tabler(
  $table = 'tag',
  $tableId = 'id',
  $nameSingular = 'tag',
  $namePlural = 'tags',
  $publicLink = '../tags/',
  $col = array(
    array('class'=>'col-md-6', 'title'=>'name', 'key'=>'name'),
    array('class'=>'col-md-6', 'title'=>'code', 'key'=>'code'),
  ),
  $sql = 'SELECT id, code, name FROM tag ORDER BY name',
  $adminLink = '../tags-admin/',
  $showEdit = false,
  $perPage = 100
);

$this->pageFooter();
