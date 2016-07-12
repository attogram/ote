<?php // Open Translation Engine - Words Admin v0.2.3

namespace Attogram;

$this->pageHeader('Words Admin');

$this->database->tabler(
  $table = 'word',
  $tableId = 'id',
  $nameSingular = 'word',
  $namePlural = 'words',
  $publicLink = '../word/',
  $col = array(
    array('class'=>'col-md-8', 'title'=>'word', 'key'=>'word'),
    array('class'=>'col-md-3', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = 'SELECT word, id FROM word ORDER BY word',
  $adminLink = '../words-admin/',
  $showEdit = true,
  $perPage = 1000
);

$this->pageFooter();
