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
    array('class'=>'col-md-4', 'title'=>'s_word', 'key'=>'s_word'),
    array('class'=>'col-md-2', 'title'=>'s_code', 'key'=>'s_code'),
    array('class'=>'col-md-4', 'title'=>'t_word', 'key'=>'t_word'),
    array('class'=>'col-md-2', 'title'=>'t_code', 'key'=>'t_code'),
  ),
  $sql = '
SELECT
  ww.id,
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
ORDER BY sw.word, tw.word
',
  $admin_link = '../word2word-admin/',
  $show_edit = FALSE
);

$this->page_footer();
