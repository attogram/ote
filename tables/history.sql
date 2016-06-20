-- Open Translation Engine - history table v0.0.1

CREATE TABLE IF NOT EXISTS 'history' (
  'id' INTEGER PRIMARY KEY,
  'word' TEXT NOT NULL DEFAULT '', -- The Word
  'count' NUMERIC NOT NULL DEFAULT '0', -- number of searches for this word for this date
  'date' NUMERIC NOT NULL DEFAULT '0000-00-00', -- The Date in format: YYYY-MM-DD
  'sl' TEXT NOT NULL DEFAULT '', -- The Source Language ID
  'tl' TEXT NOT NULL DEFAULT '', -- The Target Language ID
  unique( word, date, sl, tl )
);
