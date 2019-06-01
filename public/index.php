<?php
/**
 * Open Translation Engine v2
 * Public Index File
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 */
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
