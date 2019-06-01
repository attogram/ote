<?php
/**
 * Open Translation Engine v2
 * Repository
 *
 * @see https://github.com/attogram/ote
 * @license MIT
 */
declare(strict_types = 1);

namespace Attogram\OpenTranslationEngine;

use Attogram\Database\Database;
use Exception;

class Repository
{
    /** @var Database - Attogram Database */
    private $database;

    /**
     * Repository constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->database = new Database();
        $this->database->setDatabaseFile(__DIR__ . '/../db/ote.sqlite');
        $this->database->setCreateTables($this->getTables());
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getDictionaryCount(): int
    {
        return count($this->getDictionaries());
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDictionaries(): array
    {
        return $this->database->query(
            'SELECT distinct sl, tl FROM word2word WHERE sl != tl'
        );
    }

    /**
     * @param string $table
     * @param string $id
     * @return int
     * @throws Exception
     */
    public function getCount(string $table, string $id = 'id'): int
    {
        $count = $this->database->query("SELECT COUNT($id) AS count FROM $table");
        return !empty($count[0]['count'])
            ? (int) $count[0]['count']
            : 0;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getLanguages(): array
    {
        return $this->database->query('SELECT * FROM language');
    }

    /**
     * @param int $languageId
     * @return int
     * @throws Exception
     */
    public function getDictionaryCountForLanguage(int $languageId): int
    {
        $count = $this->database->query(
            'SELECT count(distinct id) as count FROM word2word WHERE sl = :languageId OR tl = :languageId',
            ['languageId' => $languageId]
        );

        return (int) $count[0]['count'];
    }

    /**
     * @param int $languageId
     * @return int
     * @throws Exception
     */
    public function getWordCountForLanguage(int $languageId): int
    {
        $count = $this->database->query(
            'SELECT count(distinct sw) AS count FROM word2word WHERE sl = :languageId',
            ['languageId' => $languageId]
        );

        return (int) $count[0]['count'];
    }

    /**
     * @param int $languageId
     * @return int
     * @throws Exception
     */
    public function getTranslationCountForLanguage(int $languageId): int
    {
        $count = $this->database->query(
            'SELECT count(sw) AS count FROM word2word WHERE sl = :languageId',
            ['languageId' => $languageId]
        );

        return (int) $count[0]['count'];
    }

    /**
     * @return array
     */
    private function getTables(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS 'history' (
              'id'    INTEGER PRIMARY KEY,
              'word'  TEXT    NOT NULL DEFAULT '',           -- The Word
              'count' INTEGER NOT NULL DEFAULT '0',          -- number of searches for this word for this date
              'date'  NUMERIC NOT NULL DEFAULT '0000-00-00', -- The Date in format: YYYY-MM-DD
              'sl'    INTEGER NOT NULL DEFAULT '0',          -- The Source Language ID
              'tl'    INTEGER NOT NULL DEFAULT '0',          -- The Target Language ID
              unique(word, date, sl, tl)
            );",
            "CREATE TABLE IF NOT EXISTS 'language' (
              'id'   INTEGER PRIMARY KEY,
              'code' TEXT UNIQUE NOT NULL DEFAULT '', -- The Language Code
              'name' TEXT UNIQUE NOT NULL DEFAULT ''  -- The Language Name
            );",
            "CREATE TABLE IF NOT EXISTS 'slush_pile' (
              'id'                   INTEGER PRIMARY KEY,
              'user_id'              INTEGER NOT NULL DEFAULT '0',
              'date'                 NUMERIC NOT NULL DEFAULT '0000-00-00', -- The Date in format: YYYY-MM-DD
              'type'                 TEXT,                                  -- Type: add, delete
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
            );",
            "CREATE TABLE IF NOT EXISTS 'tag' (
              'id'   INTEGER PRIMARY KEY,
              'code' TEXT UNIQUE NOT NULL DEFAULT '', -- The Tag Code (abbreviation)
              'name' TEXT UNIQUE NOT NULL DEFAULT ''  -- The Tag Full Name
            );",
            "CREATE TABLE IF NOT EXISTS 'tag2word' (
              'id'  INTEGER PRIMARY KEY,
              'w2w' INTEGER NOT NULL DEFAULT '0', -- Translation Pair ID = word2word.id
              'tag' INTEGER NOT NULL DEFAULT '0', -- Tag ID = tag.id
              unique(w2w, tag)
            );",
            "CREATE TABLE IF NOT EXISTS 'word' (
              'id'  INTEGER PRIMARY KEY,
              'word' TEXT UNIQUE NOT NULL DEFAULT '' -- The Word!
            );",
            "CREATE TABLE IF NOT EXISTS 'word2word' (
              'id' INTEGER PRIMARY KEY,
              'sw' INTEGER NOT NULL DEFAULT '0', -- Source Word     = word.id
              'sl' INTEGER NOT NULL DEFAULT '0', -- Source Language = language.id
              'tw' INTEGER NOT NULL DEFAULT '0', -- Target Word     = word.id
              'tl' INTEGER NOT NULL DEFAULT '0', -- Target Language = language.id
              unique(sw,sl,tw,tl) -- Unique Key: Source Word + Source Language + Target Word + Target Language
            );",
        ];
    }
}
