<?php
namespace Attogram;

$this->page_header('Languages - OTE 1.0.0');

$lang = $this->sqlite_database->query('SELECT code, language FROM language ORDER BY id');

print '<div class="container"><p><strong>' . count($lang) . '</strong> <a href="">Languages</a>';
if( $this->is_admin()) {
  print ' &nbsp; - &nbsp; <a href="../languages-admin/">Admin</a>';
}
print '<table class="table table-bordered"><thead><tr>
<th>code</th>
<th>language</th>
</tr></thead><tbody>';
foreach($lang as $u) {
  print '<tr><td>' . htmlentities($u['code']) . '</td>';
  print '<td>' . htmlentities($u['language']) . '</td></tr>';
}
print '</tbody></table></div>';

$this->page_footer();
