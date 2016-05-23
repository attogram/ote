<?php
namespace Attogram;

$this->page_header('Languages Admin - OTE 1.0.0');

$lang = $this->sqlite_database->query('SELECT * FROM language ORDER BY id');

print '<div class="container"><p><strong>' . count($lang) . '</strong> <a href="">Languages</a>';
print ' &nbsp; - &nbsp; <a href="../languages/">view</a>';
print ' &nbsp; - &nbsp; <a target="_db" href="../db-admin/?table=language&action=row_create">Create New Language</a></p>';
print '<table class="table table-bordered"><thead><tr>
<th>ID</th><th>edit</th><th>delete</th>
<th>code</th>
<th>language</th>
</tr></thead><tbody>
<tr>';
foreach($lang as $u) {
  print '<tr><td>' . $u['id'] . '</td>';
  print '<td><a target="_db" href="../db-admin/?table=language&action=row_editordelete&pk=[' . $u['id'] . ']&type=edit">edit</a></td>';
  print '<td><a target="_db" href="../db-admin/?table=language&action=row_editordelete&pk=[' . $u['id'] . ']&type=delete">delete</a></td>';
  print '<td>' . htmlentities($u['code']) . '</td>';
  print '<td>' . htmlentities($u['language']) . '</td>';
}
print '</table></div>';

$this->page_footer();
