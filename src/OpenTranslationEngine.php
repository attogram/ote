<?php
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

use Attogram\Database\Database;
use Attogram\Router\Router;

use function file_exists;
use function file_get_contents;
use function header;
use function is_readable;

class OpenTranslationEngine
{
    const OTE_NAME    = 'Open Translation Engine';
    const OTE_VERSION = '2.0.0-alpha.1';

    /** @var Router - Attogram Router */
    private $router;

    /** @var Database - Attogram Database - SQLite */
    private $database;

    /** @var array - Template Data */
    private $data = [];

    public function __construct()
    {
        $this->database = new Database();
        $this->database->setDatabaseFile(__DIR__ . '/../db/ote.sqlite');
        include(__DIR__ . '/../ote.tables.php');
        /** @var array $tables */
        foreach ($tables as $table) {
            $this->database->setCreateTables($table);
        }
        print '<pre>';print_r($this->database);
        print_r($this->database->query('SELECT * FROM word'));
    }

    public function route()
    {
        $this->router = new Router;
        $this->router->setForceSlash(true);
        $this->setPublicRoutes();
        $this->setAdminRoutes();
        $control = $this->router->match();
        if ($control) {
            /** @var string $control */
            $this->pageHeader();
            $this->includeTemplate($control);
            $this->pageFooter();

            return;
        }

        $this->pageNotFound();

        return;
    }

    private function setPublicRoutes()
    {
        $this->router->allow('/', 'home');
        $this->router->allow('/languages/', 'languages');
        $this->router->allow('/dictionary/', 'dictionary');
        $this->router->allow('/word/', 'word');
        $this->router->allow('/slush_pile/', 'slush_pile');
        $this->router->allow('/search/', 'search');
        $this->router->allow('/export/', 'export');
        $this->router->allow('/history/', 'history');
        $this->router->allow('/readme/', 'readme');
    }

    private function setAdminRoutes()
    {
        $this->router->allow('/import/',          'import');
        $this->router->allow('/languages-admin/', 'languages-admin');
        $this->router->allow('/tags-admin/',      'tags-admin');
        $this->router->allow('/events/',          'events');
        $this->router->allow('/info/',            'info');
        $this->router->allow('/db-admin/',        'db-admin');
        $this->router->allow('/db-tables/',       'db-tables');
        $this->router->allow('/find_3rd_level/',  'find_3rd_level');
        $this->router->allow('/check.php',        'check.php');
    }

    /**
     * @param string $message
     */
    private function pageNotFound(string $message = '404 Page Not Found')
    {
        header('HTTP/1.0 404 Not Found', true, 404);
        $this->pageHeader();
        print $message;
        $this->pageFooter();
    }

    /**
     * @param string $title (optional)
     */
    private function pageHeader(string $title = '')
    {
        if (!$title) {
            $title = OpenTranslationEngine::OTE_NAME . ' v' . OpenTranslationEngine::OTE_VERSION;
        }
        $this->data['title'] = $title;
        $this->data['webHome'] = $this->router->getHome();
        $this->includeTemplate('header');
    }

    private function pageFooter()
    {
        $this->includeTemplate('footer');
    }

    /**
     * @param string $control
     */
    private function includeTemplate(string $control)
    {
        $template = __DIR__ . '/../template/' . $control . '.php';
        if (file_exists($template) && is_readable($template)) {
            /** @noinspection PhpIncludeInspection */
            include $template;

            return;
        }

        print 'Template [' . $control . '] Not Found';

        return;
    }

    /**
     * @param string $index
     * @return string
     */
    public function getData(string $index): string
    {
        if (!empty($this->data[$index])) {
            return $this->data[$index];
        }

        return '';
    }

    public function getLanguagesCount()
    {
        return 0;
    }

    public function getDictionaryCount()
    {
        return 0;
    }

    public function getWordCount()
    {
        return 0;
    }

    public function getCountSlushPile()
    {
        return 0;
    }

    public function getSiteUrl()
    {
        return '';
    }
}
