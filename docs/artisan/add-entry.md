# `ote:add-entry`

The `ote:add-entry` command creates a new lexical entry, which serves as a bridge between a token and a language.

## Usage

```bash
php artisan ote:add-entry {token} {language}
```

## Arguments

-   `{token}`: The text of the token you want to create an entry for.
-   `{language}`: The language code (e.g., "en", "es") for the entry.

## Description

This command is used to associate a token with a specific language, creating a lexical entry. A lexical entry is a fundamental concept in OTE, representing a word or phrase in a particular language.

Before you can create an entry, both the token and the language must already exist in the database. If either the token or the language is not found, the command will fail and prompt you to create them first using the `ote:add-token` and `ote:add-language` commands.

If the lexical entry for the given token and language already exists, the command will notify you and will not create a duplicate.

## Example

To create a lexical entry for the token "hello" in English ("en"), you would run:

```bash
php artisan ote:add-entry hello en
```

If the entry is created successfully, you will see a confirmation message:

```
Lexical entry for 'hello' in 'en' created successfully with ID {new_entry_id}.
```

If the entry already exists, you will see a warning:

```
Lexical entry already exists with ID {existing_entry_id}.
```
