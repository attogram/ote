<?php // Open Translation Engine - word2word Admin v0.1.1

namespace Attogram;

$this->page_header('Word2Word Admin');

list( $limit, $offset ) = $this->db->get_set_limit_and_offset(
  $default_limit  = 250,
  $default_offset = 0,
  $max_limit      = 1000,
  $min_limit      = 10
);

print $this->db->pager( $d_all, $limit, $offset );

$this->db->tabler(
  $table = 'word2word',
  $name_singular = 'word2word',
  $name_plural = 'word2words',
  $public_link = false,
  $col = array(
    array('class'=>'col-md-2', 'title'=>'Source Word', 'key'=>'s_word'),
    array('class'=>'col-md-1', 'title'=>'sw', 'key'=>'sw'),
    array('class'=>'col-md-1', 'title'=>'sl', 'key'=>'sl'),
    array('class'=>'col-md-2', 'title'=>'Target Word', 'key'=>'t_word'),
    array('class'=>'col-md-1', 'title'=>'tw', 'key'=>'tw'),
    array('class'=>'col-md-1', 'title'=>'tl', 'key'=>'tl'),
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
  sw.id = ww.sw
AND
  tw.id = ww.tw
ORDER BY sw.word, tw.word

LIMIT 100
',
  $admin_link = '../word2word-admin/',
  $show_edit = true
);

$this->page_footer();
