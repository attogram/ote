<?php
// Open Translation Engine - word2word Admin v0.0.2

namespace Attogram;

$this->page_header('Word2Word Admin');

tabler(
  $attogram = $this,
  $table = 'word2word',
  $name_singular = 'word2word',
  $name_plural = 'word2words',
  $public_link = '../word2word/',
  $col = array(
    array('class'=>'col-md-2', 'title'=>'s_word', 'key'=>'s_word'),
    array('class'=>'col-md-1', 'title'=>'s_id', 'key'=>'s_id'),
    array('class'=>'col-md-1', 'title'=>'s_code', 'key'=>'s_code'),
    array('class'=>'col-md-1', 'title'=>'s_code_id', 'key'=>'s_code_id'),
    array('class'=>'col-md-2', 'title'=>'t_word', 'key'=>'t_word'),
    array('class'=>'col-md-1', 'title'=>'t_id', 'key'=>'t_id'),
    array('class'=>'col-md-1', 'title'=>'t_code', 'key'=>'t_code'),
    array('class'=>'col-md-1', 'title'=>'t_code_id', 'key'=>'t_code_id'),
    array('class'=>'col-md-1', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = '
SELECT
  ww.*,
  sw.word AS s_word,
  tw.word AS t_word
FROM
  word2word AS ww,
  word AS sw,
  word AS tw
WHERE
  sw.id = ww.s_id
AND
  tw.id = ww.t_id
ORDER BY sw.word, tw.word
',
  $admin_link = '../word2word-admin/',
  $show_edit = TRUE
);

$this->page_footer();
