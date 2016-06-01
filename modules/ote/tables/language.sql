-- Open Translation Engine - language table v0.0.1

CREATE TABLE IF NOT EXISTS 'language' (
  'id'       INTEGER PRIMARY KEY,
  'code'     TEXT UNIQUE NOT NULL DEFAULT '',
  'language' TEXT UNIQUE NOT NULL DEFAULT ''
)
