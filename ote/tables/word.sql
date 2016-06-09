-- Open Translation Engine - word table v0.0.8

CREATE TABLE IF NOT EXISTS 'word' (
  'id' INTEGER PRIMARY KEY,
  'word' TEXT UNIQUE NOT NULL DEFAULT '' -- The Word!
)
