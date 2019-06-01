<?php
/**
 * Open Translation Engine v2
 * Controller
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 *
*/
/** @noinspection PhpUnusedPrivateMethodInspection */
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

use Attogram\Router\Router;
use Exception;

use function header;
use function method_exists;

class OpenTranslationEngine
{
    use TemplateTrait;

    const OTE_NAME    = 'Open Translation Engine';
    const OTE_VERSION = '2.0.0-alpha.5';
    const OTE_REPO    = 'https://github.com/attogram/ote';

    /** @var Router - Attogram Router */
    private $router;

    /** @var Repository - Translation Database access */
    private $repository;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->router = new Router;
        $this->repository = new Repository();
        $this->user = new User($this->repository);
    }

    public function route()
    {
        $this->router->setForceSlash(true);
        $this->setPublicRoutes();
        if ($this->user->isAdmin()) {
            $this->setAdminRoutes();
        }
        /** @var string|null $control */
        $control = $this->router->match();
        if (!$control) {
            $this->pageNotFound();

            return;
        }
        if (method_exists($this, $control)) {
            $this->{$control}(); // call the function for this control
        }
        $this->pageHeader();
        $this->includeTemplate($control); // include the template for this control
        $this->pageFooter();
    }

    private function setPublicRoutes()
    {
        $this->router->allow('/',                'home');
        $this->router->allow('/languages/',      'languages');
        $this->router->allow('/dictionary/',     'dictionary');
        $this->router->allow('/dictionary/?/',   'dictionary');
        $this->router->allow('/dictionary/?/?/', 'dictionary');
        $this->router->allow('/word/',           'word');
        $this->router->allow('/slush_pile/',     'slush_pile');
        $this->router->allow('/search/',         'search');
        $this->router->allow('/export/',         'export');
        $this->router->allow('/history/',        'history');
    }

    private function setAdminRoutes()
    {
        $this->router->allow('/import/',          'import');
        $this->router->allow('/languages-admin/', 'languages-admin');
        $this->router->allow('/tags-admin/',      'tags-admin');
        $this->router->allow('/events/',          'events');
        $this->router->allow('/info/',            'info');
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

    private function pageHeader()
    {
        $this->data['title'] = self::OTE_NAME . ' v' . self::OTE_VERSION;
        $this->data['repo'] = self::OTE_REPO;
        $this->data['webHome'] = $this->router->getHome();
        $this->includeTemplate('header');
    }

    private function pageFooter()
    {
        $this->includeTemplate('footer');
    }

    /**
     * @return string
     */
    private function getSiteUrl(): string
    {
        return $this->router->getCurrentFull();
    }

    /**
     * @route /
     * @throws Exception
     */
    private function home()
    {
        $this->data['headline']        = self::OTE_NAME . ' v' . self::OTE_VERSION;
        $this->data['subhead']         = 'a collaborative translation dictionary';
        $this->data['languageCount']   = $this->repository->getCount('language');
        $this->data['dictionaryCount'] = $this->repository->getDictionaryCount();
        $this->data['wordCount']       = $this->repository->getCount('word');
        $this->data['slushPileCount']  = $this->repository->getCount('slush_pile');
        $this->data['userCount']       = 0;
        $this->data['eventCount']      = 0;
    }

    /**
     * @route /languages/
     * @throws Exception
     */
    private function languages()
    {
        $this->data['languageCount'] = $this->repository->getCount('language');
        $this->data['languages'] = $this->repository->getLanguages();
        foreach ($this->data['languages'] as $index => $language) {
            $languageId = (int) $language['id'];
            $this->data['languages'][$index]['dictionaryCount']
                = $this->repository->getDictionaryCountForLanguage($languageId);
            $this->data['languages'][$index]['wordCount']
                = $this->repository->getWordCountForLanguage($languageId);
            $this->data['languages'][$index]['translationCount']
                = $this->repository->getTranslationCountForLanguage($languageId);
        }
    }

    /**
     * @route /dictionary/
     * @route /dictionary/{sourceLanguage}/
     * @route /dictionary/{sourceLanguage}/{targetLanguage}/
     * @throws Exception
     */
    private function dictionary()
    {
        $this->data['sourceLanguage']  = $this->router->getVar(0) ?? '';
        $this->data['targetLanguage']  = $this->router->getVar(1) ?? '';
        $this->data['dictionaries']    = $this->repository->getDictionaries();
        $this->data['dictionaryCount'] = count($this->data['dictionaries']);
    }
}
