-- Open Translation Engine - slush_pile table v0.0.1

CREATE TABLE IF NOT EXISTS 'slush_pile' (
  'id'                   INTEGER PRIMARY KEY,
  'user_id'              INTEGER NOT NULL DEFAULT '0',
  'date'                 NUMERIC NOT NULL DEFAULT '0000-00-00', -- The Date in format: YYYY-MM-DD
  'source_word'          TEXT,
  'source_word_id'       INTEGER NOT NULL DEFAULT '0',
  'source_language_id'   INTEGER NOT NULL DEFAULT '0',
  'source_language_code' TEXT,
  'source_language_name' TEXT,
  'target_word'          TEXT,
  'target_word_id'       INTEGER NOT NULL DEFAULT '0',
  'target_language_id'   INTEGER NOT NULL DEFAULT '0',
  'target_language_code' TEXT,
  'target_language_name' TEXT,
  'word2word_id'         INTEGER NOT NULL DEFAULT '0',
  'tag_id'               INTEGER NOT NULL DEFAULT '0',
  'tag_code'             TEXT,
  'tag_name'             TEXT
)
