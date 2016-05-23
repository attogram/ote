<?php
namespace Attogram;

$this->page_header('Export - OTE v1.0.0-dev');

$sql = '
SELECT
  sw.word AS s_word,
  ww.s_code,
  tw.word AS t_word,
  ww.t_code
FROM
  word2word AS ww,
  word AS sw,
  word AS tw
WHERE
  sw.id = ww.s_id
AND
  tw.id = ww.t_id
';

$result = $this->sqlite_database->query($sql);
$sep = ', ';
?>
<div class="container">
<h1>Export</h1>
<textarea class="form-control" rows="10" id="export">
# Source_word<?php print $sep; ?>Target_word<?php print $sep; ?>Source_language_code<?php print $sep; ?>Target_language_code
<?php
foreach( $result as $r ) {
  print $r['s_word'] . $sep . $r['t_word'] 
  . $sep . $r['s_code'] . $sep . $r['t_code'] . "\n";
}
?></textarea>
</div>
<?php
$this->page_footer();
