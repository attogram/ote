<?php
/**
 * Open Translation Engine v2
 * Template Trait
 *
 * @license MIT
 * @see https://github.com/attogram/ote
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
        if (isset($this->data[$index])) {
            return $this->data[$index];
        }

        return '?';
    }

    /**
     * @param string $index
     * @return int
     */
    public function getDataInt(string $index): int
    {
        if (isset($this->data[$index])) {
            return (int) $this->data[$index];
        }

        return 0;
    }

    /**
     * @param string $index
     * @return array
     */
    public function getDataArray(string $index): array
    {
        if (isset($this->data[$index]) && is_array($this->data[$index])) {
            return $this->data[$index];
        }

        return [];
    }
}