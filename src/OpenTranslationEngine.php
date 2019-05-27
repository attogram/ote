<?php
/**
 * Open Translation Engine
 * @license MIT
 * @see https://github.com/attogram/ote
 */
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

use Attogram\Router\Router;

use function file_exists;
use function header;
use function is_readable;

class OpenTranslationEngine
{
    const OTE_NAME    = 'Open Translation Engine';
    const OTE_VERSION = '2.0.0-alpha.2';

    /** @var Router - Attogram Router */
    private $router;

    /** @var Repository - access to Translation Database */
    private $repository;

    /** @var array - Template Data */
    private $data = [];

    public function __construct()
    {
        $this->repository = new Repository();
        $this->router = new Router;
    }

    public function route()
    {
        $this->router->setForceSlash(true);
        $this->setPublicRoutes();
        $this->setAdminRoutes();
        /** @var string $control */
        $control = $this->router->match();
        if (!$control) {
            $this->pageNotFound();

            return;
        }
        if (method_exists($this, $control)) {
            $this->{$control}();
        }
        $this->pageHeader();
        $this->includeTemplate($control);
        $this->pageFooter();
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

    // v1 ---

    public function getSiteUrl()
    {
        return '';
    }
}
