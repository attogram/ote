-- Open Translation Engine - tag table v0.0.3

CREATE TABLE IF NOT EXISTS 'tag' (
  'id'   INTEGER PRIMARY KEY,
  'code' TEXT UNIQUE NOT NULL DEFAULT '', -- The Tag Code (abbreviation)
  'name' TEXT UNIQUE NOT NULL DEFAULT ''  -- The Tag Full Name
);

-- INSERT INTO 'tag' (code, name) VALUES ('n','noun');
-- INSERT INTO 'tag' (code, name) VALUES ('pr','proper noun');
-- INSERT INTO 'tag' (code, name) VALUES ('pron','pronoun');
-- INSERT INTO 'tag' (code, name) VALUES ('adj','adjective');
-- INSERT INTO 'tag' (code, name) VALUES ('v','verb');
-- INSERT INTO 'tag' (code, name) VALUES ('aux','auxiliary verb');
-- INSERT INTO 'tag' (code, name) VALUES ('be','be verb');
-- INSERT INTO 'tag' (code, name) VALUES ('adv','adverb');
-- INSERT INTO 'tag' (code, name) VALUES ('prep','preposition');
-- INSERT INTO 'tag' (code, name) VALUES ('conj','conjunction');
-- INSERT INTO 'tag' (code, name) VALUES ('interj','interjection');
-- INSERT INTO 'tag' (code, name) VALUES ('sg','singular');
-- INSERT INTO 'tag' (code, name) VALUES ('pl','plural');
-- INSERT INTO 'tag' (code, name) VALUES ('fem','feminine');
-- INSERT INTO 'tag' (code, name) VALUES ('masc','masculine');
-- INSERT INTO 'tag' (code, name) VALUES ('neut','neuter gender');
-- INSERT INTO 'tag' (code, name) VALUES ('form','formal');
-- INSERT INTO 'tag' (code, name) VALUES ('inform','informal');
-- INSERT INTO 'tag' (code, name) VALUES ('num','numeral');
-- INSERT INTO 'tag' (code, name) VALUES ('hon','honorific');
-- INSERT INTO 'tag' (code, name) VALUES ('slang','slang');
