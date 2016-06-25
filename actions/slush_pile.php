<?php // Open Translation Engine - Slush Pile Page v0.0.8

namespace Attogram;

if( $_GET ) {
  if(
    !isset($_GET['a']) || !( $_GET['a'] == 'a' || $_GET['a'] == 'd' )
    || !isset($_GET['i']) || !$_GET['i'] || !is_numeric($_GET['i'])
  ) {
    $this->error404('Slush pile options not slushable.');
  }
  $action = urldecode($_GET['a']);
  $slush_id = urldecode($_GET['i']);
  switch( $action ) {
    case 'a':
      print "<p>ACTION:$action slush_id:$slush_id sql:$sql bind:" . print_r($bind,1) . "<p>";
      break;
    case 'd':
      $sql = 'DELETE FROM slush_pile WHERE id = :id';
      $bind['id'] = $slush_id;
      if( $this->db->queryb( $sql, $bind ) ) {
        print '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        . '<strong>Deleted</strong> Slush Pile ID #' . htmlentities($slush_id) . '</div>';
      } else {
        print '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        . '<strong>Error</strong> - can not delete Slush Pile ID #' . htmlentities($slush_id) . '</div>';      }
      break;
  }
} // end if _GET


$this->page_header('🛃 Slush Pile');
print '<div class="container">';
print '<h1 class="squished"><a href="./" style="color:inherit;text-decoration:none;">🛃</a> Slush Pile</h1>';

$ote = new ote( $this->db, $this->log );

list( $limit, $offset ) = $ote->db->get_set_limit_and_offset(
  $default_limit  = 15,
  $default_offset = 0,
  $max_limit      = 1000,
  $min_limit      = 5
);

$sql = 'SELECT * FROM slush_pile ORDER BY id DESC LIMIT ' . $limit;
if( $offset ) {
  $sql .= ", $offset";
}
$slush = $this->db->query($sql);

print $this->db->pager(
  $ote->get_count_slush_pile(),
  $limit,
  $offset,
  $prepend_query_string = ''
);

foreach( $slush as $s ) {
  $id = isset($s['id']) ? $s['id'] : 0;
  $date = isset($s['date']) ? htmlentities($s['date']) : '';
  $type = isset($s['type']) ? htmlentities($s['type']) : '';
  $user_id = isset($s['user_id']) ? htmlentities($s['user_id']) : '';
  $source_word = isset($s['source_word']) ? htmlentities($s['source_word']) : '';
  $source_word_url = '../word///' . urlencode($s['source_word']);
  $source_language_code = isset($s['source_language_code']) ? htmlentities($s['source_language_code']) : '';
  $target_word = isset($s['target_word']) ? htmlentities($s['target_word']) : '';
  $target_word_url = '../word///' . urlencode($s['target_word']);
  $target_language_code = isset($s['target_language_code']) ? htmlentities($s['target_language_code']) : '';
  print '<div class="row" style="border:solid 1px #ccc;padding:4px;">'
  . '<div class="col-sm-4">🕑:' . $date . ' 👤:' . $user_id . ' 🔀:<strong>' . $type . '</strong></div>'
  . '<div class="col-sm-4"><a href="' . $source_word_url . '">' . $source_word . '</a> <code>' . $source_language_code . '</code>'
  . ' = <a href="' . $target_word_url . '">' . $target_word . '</a> <code>' . $target_language_code . '</code></div>'
  . '<div class="col-sm-4">'
  . '<a href="?a=a&i=' . urlencode($id) . '">✔ Accept</a>'
  . ' &nbsp; <a href="?a=d&i=' . urlencode($id) . '">✖ Delete</a></div>'
  . '</div>';
}

print '</div>';

$this->page_footer();
