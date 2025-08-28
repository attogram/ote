# `ote:add-language`

The `ote:add-language` command adds a new language to the system.

## Usage

```bash
php artisan ote:add-language {code} {name}
```

## Arguments

-   `{code}`: The language code, which should be a unique identifier (e.g., "en" for English, "fr" for French). It's recommended to use [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) codes where possible.
-   `{name}`: The full name of the language (e.g., "English", "French").

## Description

This command is used to register a new language in the Open Translation Engine. Each language needs a unique code and a descriptive name. Once a language is added, you can start creating lexical entries for it.

If you attempt to add a language with a code that already exists, the command will fail and show an error message.

## Example

To add French to the system, you would run the following command:

```bash
php artisan ote:add-language fr French
```

Upon successful creation, you will see a confirmation message:

```
Language 'French' (fr) added successfully with ID {new_language_id}.
```
