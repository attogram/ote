<?php
// Open Translation Engine
// 404.php

require_once('settings.php');

header('HTTP/1.0 404 Not Found');

print
'<html><head><title>404 Page Not Found</title></head><body>
<h1><a href="' . PROTOCOL . HOST . OTE_DIRECTORY . '">Open Translation Engine Homepage</a></h1>
<p>Error 404: Page Not Found.</p>
</body></html>';

