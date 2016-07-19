<?php // Open Translation Engine - Slush Pile Page v0.2.1

namespace Attogram;

$ote = new OpenTranslationEngine($this);

$this->pageHeader('🛃 Slush Pile');
print '<div class="container">';

if ($_GET ) {
  if (!isset($_GET['a']) || !($_GET['a'] == 'a' || $_GET['a'] == 'd' )
   || !isset($_GET['i']) || !$_GET['i'] || !is_numeric($_GET['i'])
  ) {
    $this->error404('Slush pile options not slushable.');
  }
  $action = urldecode($_GET['a']);
  $slushId = urldecode($_GET['i']);
  switch($action ) {
    case 'a': // Accept slush pile entry
      if ($ote->acceptSlushPileEntry($slushId )) {
        print '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        . '<strong>Accepted</strong>: ' . $_SESSION['result'] . '</div>';
        unset($_SESSION['result']);
      } else {
        print '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        . '<strong>Error</strong>: ' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
      }
      break;
    case 'd':  // Delete slush pile entry
      if ($ote->deleteFromSlushPile($slushId )) {
        print '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        . '<strong>Deleted</strong>: Slush Pile ID #' . $this->webDisplay($slushId) . '</div>';
      } else {
        print '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
        . '<strong>Error</strong>: ' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
      }
      break;
  }
} // end if _GET

print '<h1 class="squished"><a href="./" style="color:inherit;text-decoration:none;">🛃</a> Slush Pile</h1>';

list($limit, $offset ) = $this->database->getSetLimitAndOffset(
  15, // $defaultLimit
  0, // $defaultOffset
  1000, // $maxLimit
  5 // $minLimit
);

$sql = 'SELECT * FROM slush_pile ORDER BY id DESC LIMIT ' . $limit;
if ($offset ) {
  $sql .= ", $offset";
}
$slush = $this->database->query($sql);

print $this->database->pager(
  $ote->getCountSlushPile(),
  $limit,
  $offset,
  $prepend_query_string = ''
);

foreach ($slush as $s) {
  $id = isset($s['id']) ? $s['id'] : 0;
  $date = isset($s['date']) ? $this->webDisplay($s['date']) : '';
  $type = isset($s['type']) ? $this->webDisplay($s['type']) : '';
  $user_id = isset($s['user_id']) ? $this->webDisplay($s['user_id']) : '';
  $sourceWord = isset($s['source_word']) ? $this->webDisplay($s['source_word']) : '';
  $sourceWord_url = '../word///' . urlencode($s['source_word']);
  $sourceLanguageCode = isset($s['source_language_code']) ? $this->webDisplay($s['source_language_code']) : '';
  $targetWord = isset($s['target_word']) ? $this->webDisplay($s['target_word']) : '';
  $targetWord_url = '../word///' . urlencode($s['target_word']);
  $targetLanguageCode = isset($s['target_language_code']) ? $this->webDisplay($s['target_language_code']) : '';
  print '<div class="row" style="border:solid 1px #ccc;padding:4px;">'
  . '<div class="col-sm-4">🕑:' . $date . ' 👤:' . $user_id . ' 🔀:<strong>' . $type . '</strong></div>'
  . '<div class="col-sm-4"><code>' . $sourceLanguageCode . '</code> <a href="' . $sourceWord_url . '">' . $sourceWord . '</a>'
  . ' = <a href="' . $targetWord_url . '">' . $targetWord . '</a> <code>' . $targetLanguageCode . '</code></div>'
  . '<div class="col-sm-4">'
  . '<a href="?a=a&i=' . urlencode($id) . '">✔ Accept</a>'
  . ' &nbsp; <a href="?a=d&i=' . urlencode($id) . '">✖ Reject</a></div>'
  . '</div>';
}

print '</div>';

$this->pageFooter();
