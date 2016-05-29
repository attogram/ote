<?php
// Attogram Framework - Footer v0.0.2

namespace Attogram;

?>
<footer class="footer">
 <div class="container">
  <p><small>
    Powered by <a target="github" href="https://github.com/attogram/attogram">Attogram Framework v<?php print ATTOGRAM_VERSION; ?></a>
    &nbsp; | &nbsp; <?php print gmdate('Y-m-d H:i:s'); ?> UTC
  </small></p>
 </div>
</footer>
<?php
if( isset($debug) && $debug && isset($this->log->stack) && $this->log->stack ) {
  print '<div class="container"><pre class="alert alert-debug">System Debug:<br />'
  . implode($this->log->stack, '<br />')
  . '</pre></div>';
}

if( isset($this->db->log->stack) && $this->db->log->stack ) {
  print '<div class="container"><pre class="alert alert-debug">Database Debug:<br />'
  . implode($this->db->log->stack, '<br />')
  . '</pre></div> ';}
?>
</body></html>
