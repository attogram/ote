<?php // Open Translation Engine - footer template v0.0.2

namespace Attogram;

$divider = '&nbsp;&nbsp; | &nbsp;&nbsp;';
print '
<footer class="footer">
 <div class="container-fluid">
  <p>
    <nobr><a href="' . $this->get_site_url() . '/">' .  $this->site_name . ' <small>v' . ote::OTE_VERSION . '</small></a></nobr>
    <small>' . $divider . '
    <nobr>🚀 Powered by <a target="github" href="' . $this->project_github . '">Attogram <small>v' . attogram::ATTOGRAM_VERSION . '</small></a></nobr>
    ' . $divider . '
    <nobr>🕑 ' . gmdate('Y-m-d H:i:s') . ' UTC</nobr>
    ' . $divider . '
    <nobr>👤 ' . $this->clientIp . '</nobr>
    ' . $divider . '
    <nobr>🏁 ' . round( (microtime(1) - $this->start_time), 3, PHP_ROUND_HALF_UP) . ' seconds</nobr></small>
  </p>
 </div>
</footer>
</body></html>';
