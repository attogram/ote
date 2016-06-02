-- Open Translation Engine - word2word table v0.0.2

CREATE TABLE IF NOT EXISTS 'word2word' (
  'id' INTEGER PRIMARY KEY,
  's_id' INTEGER NOT NULL DEFAULT '0', -- Source word.id
  's_code_id' INTEGER NOT NULL DEFAULT '0', -- Source language.id
  's_code' TEXT,
  't_id' INTEGER NOT NULL DEFAULT '0', -- Target word.id
  't_code_id' INTEGER NOT NULL DEFAULT '0', -- Target language.id
  't_code' TEXT,
  unique(s_id,s_code_id,t_id,t_code_id)
)
