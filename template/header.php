<?php
/**
 * Open Translation Engine v2
 * HTML Page Header Template
 *
 * @license MIT
 * @see https://github.com/attogram/ote
 *
 * @var OpenTranslationEngine $this
 */
use Attogram\OpenTranslationEngine\OpenTranslationEngine;

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= $this->getData('webHome'); ?>ote.css">
<title><?= $this->getData('title'); ?></title>
</head>
<body>
<div class="header">
    <a href="<?= $this->getData('webHome'); ?>">OTE - Open Translation Engine</a>
    - <a href="<?= $this->getData('webHome'); ?>languages/">Languages</a>
    - <a href="<?= $this->getData('webHome'); ?>dictionary/">Dictionaries</a>
    <span style="float:right;"><a href="<?= $this->getData('repo'); ?>">OTE @ Github</a></span>
</div>