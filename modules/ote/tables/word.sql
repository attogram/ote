-- Open Translation Engine - word table v0.0.1

CREATE TABLE IF NOT EXISTS 'word' (
  'id' INTEGER PRIMARY KEY,
  'word' TEXT UNIQUE NOT NULL DEFAULT ''
)
