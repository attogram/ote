<?php
/**
 * Open Translation Engine v2
 * Template Trait
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 */
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

use function is_array;
use function is_readable;

trait TemplateTrait
{
    /** @var array - Template Data */
    private $data = [];

    /**
     * @param string $control
     */
    private function includeTemplate(string $control)
    {
        $template = __DIR__ . '/../template/' . $control . '.php';
        if (is_readable($template)) {
            /** @noinspection PhpIncludeInspection */
            include $template;

            return;
        }

        print 'Template [' . $control . '] Not Found';

        return;
    }

    /**
     * @param string $index
     * @return mixed
     */
    public function getData(string $index)
    {
        return isset($this->data[$index])
            ? $this->data[$index]
            : '?';
    }

    /**
     * @param string $index
     * @return int
     */
    public function getDataInt(string $index): int
    {
        return isset($this->data[$index])
            ? (int) $this->data[$index]
            : 0;
    }

    /**
     * @param string $index
     * @return array
     */
    public function getDataArray(string $index): array
    {
        return (isset($this->data[$index]) && is_array($this->data[$index]))
            ? $this->data[$index]
            : [];
    }
}