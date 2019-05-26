<?php
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

$vendors = '../vendor/autoload.php';
if (!is_readable($vendors)) {
    exit('Vendor Autoload Not Found');
}
/** @noinspection PhpIncludeInspection */
require_once($vendors);

$ote = new OpenTranslationEngine();

$ote->route();
