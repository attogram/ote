<?php
namespace Attogram;

$this->page_header('Word2Word Admin - OTE 1.0.0-dev');

tabler(
  $attogram = $this,
  $table = 'word2word',
  $name_singular = 'word2word',
  $name_plural = 'word2words',
  $public_link = '../word2word/',
  $col = array(
    array('class'=>'col-md-3', 'title'=>'s_word', 'key'=>'s_word'),
    array('class'=>'col-md-1', 'title'=>'s_id', 'key'=>'s_id'),
    array('class'=>'col-md-1', 'title'=>'s_code', 'key'=>'s_code'),
    array('class'=>'col-md-3', 'title'=>'t_word', 'key'=>'t_word'),
    array('class'=>'col-md-1', 'title'=>'t_id', 'key'=>'t_id'),
    array('class'=>'col-md-1', 'title'=>'t_code', 'key'=>'t_code'),
    array('class'=>'col-md-1', 'title'=>'<code>ID</code>', 'key'=>'id'),
  ),
  $sql = '
SELECT
  ww.id,
  sw.word AS s_word,
  ww.s_id,
  ww.s_code,
  tw.word AS t_word,
  ww.t_id,
  ww.t_code
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
