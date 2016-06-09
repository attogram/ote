-- Open Translation Engine - word2word table v0.0.8

CREATE TABLE IF NOT EXISTS 'word2word' (
  'id' INTEGER PRIMARY KEY,
  'sw' INTEGER NOT NULL DEFAULT '0', -- Source Word     = word.id
  'sl' INTEGER NOT NULL DEFAULT '0', -- Source Language = language.id
  'tw' INTEGER NOT NULL DEFAULT '0', -- Target Word     = word.id
  'tl' INTEGER NOT NULL DEFAULT '0', -- Target Language = language.id
  -- Unique Key: Source Word + Source Language + Target Word + Target Language
  unique(sw,sl,tw,tl)
)
