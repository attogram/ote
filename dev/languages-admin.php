<?php
// Open Translation Engine - Languages Admin v0.2.6

namespace Attogram;

$this->pageHeader('Languages Admin');

$this->database->tabler(
    'language', // $table
    'id', // $tableId
    'language', // $nameSingular
    'languages', // $namePlural
    '../languages/', // $publicLink
    [ // $col
        ['class'=>'col-md-7', 'title'=>'name', 'key'=>'name'],
        ['class'=>'col-md-2', 'title'=>'<code>code</code>', 'key'=>'code'],
        ['class'=>'col-md-2', 'title'=>'<code>ID</code>', 'key'=>'id'],
    ],
    'SELECT name, code, id FROM language ORDER BY id', // $sql
    '../languages-admin/', // $adminLink
    true, // $showEdit
    100 // $perPage
);

$this->pageFooter();
