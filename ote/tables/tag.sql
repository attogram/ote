-- Open Translation Engine - tag table v0.0.0

CREATE TABLE IF NOT EXISTS 'tag' (
  'id'   INTEGER PRIMARY KEY,
  'name' TEXT UNIQUE NOT NULL DEFAULT ''  -- The Tag Name
)
