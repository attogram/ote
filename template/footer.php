<?php
/**
 * Open Translation Engine v2
 * HTML Page Footer
 *
 * @license MIT
 * @see https://github.com/attogram/ote
 *
 * @var OpenTranslationEngine $this
 */
use Attogram\OpenTranslationEngine\OpenTranslationEngine;

?>
<footer>
Name    : <?= $this::OTE_NAME ?> &nbsp;
Version : <?= $this::OTE_VERSION ?> &nbsp;
Home    : <a href="<?= $this->router->getHome() ?>"><?= $this->router->getHome() ?></a> &nbsp;
HomeF   : <a href="<?= $this->router->getHome() ?>"><?= $this->router->getHomeFull() ?></a> &nbsp;
Current : <a href="<?= $this->router->getCurrent() ?>"><?= $this->router->getCurrent() ?></a> &nbsp;
CurrentF: <a href="<?= $this->router->getCurrentFull() ?>"><?= $this->router->getCurrentFull() ?></a> &nbsp;
Data    : <?= print_r($this->data, true); ?> &nbsp;
getVar:0: <?= $this->router->getVar(0); ?> &nbsp;
getVar:1: <?= $this->router->getVar(1); ?> &nbsp;
getVar:2: <?= $this->router->getVar(2); ?> &nbsp;
getVar:3: <?= $this->router->getVar(3); ?> &nbsp;
</footer>
</body>
</html>
