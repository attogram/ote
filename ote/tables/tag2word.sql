-- Open Translation Engine - tag2word table v0.0.0

CREATE TABLE IF NOT EXISTS 'tag2word' (
  'id' INTEGER PRIMARY KEY,
  'w2w' INTEGER NOT NULL DEFAULT '0', -- Translation Pair ID = word2word.id
  'tag' INTEGER NOT NULL DEFAULT '0', -- Tag ID = tag.id
  unique(w2w,tag)
)
