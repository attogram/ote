<?php // Open Translation Engine - footer template v0.1.0

namespace Attogram;

$divider = '&nbsp;&nbsp; | &nbsp;&nbsp;';
print '
<footer class="footer">
 <div class="container-fluid">
  <p>
    <small>
    <span style="white-space: nowrap"><a href="' . $this->getSiteUrl() . '/">' .  $this->siteName . ' <small>v' . OpenTranslationEngine::OTE_VERSION . '</small></a></span>
    ' . $divider . '
    <span style="white-space: nowrap">🚀 Powered by <a target="github" href="' . $this->projectRepository . '">Attogram <small>v' . attogram::ATTOGRAM_VERSION . '</small></a></span>
    ' . $divider . '
    <span style="white-space: nowrap">🕑 ' . gmdate('Y-m-d H:i:s') . ' UTC</span>
    ' . $divider . '
    <span style="white-space: nowrap">👤 ' . $this->clientIp . '</span>
    ' . $divider . '
    <span style="white-space: nowrap">🏁 ' . round( (microtime(1) - $this->startTime), 3, PHP_ROUND_HALF_UP) . ' seconds</span>
    </small>
  </p>
 </div>
</footer>
</body></html>';
