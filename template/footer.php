<footer>
Name    : <?= \Attogram\OpenTranslationEngine\OpenTranslationEngine::OTE_NAME ?> &nbsp;
Version : <?= \Attogram\OpenTranslationEngine\OpenTranslationEngine::OTE_VERSION ?> &nbsp;
Home    : <a href="<?= $this->router->getHome() ?>"><?= $this->router->getHome() ?></a> &nbsp;
HomeF   : <a href="<?= $this->router->getHome() ?>"><?= $this->router->getHomeFull() ?></a> &nbsp;
Current : <a href="<?= $this->router->getCurrent() ?>"><?= $this->router->getCurrent() ?></a> &nbsp;
CurrentF: <a href="<?= $this->router->getCurrentFull() ?>"><?= $this->router->getCurrentFull() ?></a> &nbsp;
</footer>
</body>
</html>

<pre>DEBUG:

this-data

<?= print_r($this->data); ?>

</pre>
