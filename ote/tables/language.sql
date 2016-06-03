-- Open Translation Engine - language table v0.0.2

CREATE TABLE IF NOT EXISTS 'language' (
  'id'   INTEGER PRIMARY KEY,
  'code' TEXT UNIQUE NOT NULL DEFAULT '', -- The Language Code
  'name' TEXT UNIQUE NOT NULL DEFAULT ''  -- The Language Name
)
