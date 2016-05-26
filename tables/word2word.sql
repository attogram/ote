CREATE TABLE IF NOT EXISTS 'word2word' (
  'id'      INTEGER PRIMARY KEY,
  's_id'    INTEGER NOT NULL DEFAULT '0',
  's_code'  TEXT NOT NULL,
  't_id'    INTEGER NOT NULL DEFAULT '0',
  't_code'  TEXT NOT NULL,
  unique(s_id,s_code,t_id,t_code)
)