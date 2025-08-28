# `ote:delete-language`

The `ote:delete-language` command is used to delete a language and all of its associated data from the system.

## Usage

```bash
php artisan ote:delete-language {language_code}
```

## Arguments

-   `{language_code}`: The code of the language you want to delete (e.g., "fr").

## Description

This is a destructive command that permanently removes a language from the database. When a language is deleted, all lexical entries associated with that language are also deleted, along with their attributes and links. This action cannot be undone.

To find the code for a specific language, you can use the `ote:list-languages` command.

If a language with the specified code does not exist, the command will show an error message.

## Example

To delete the French language from the system, you would run:

```bash
php artisan ote:delete-language fr
```

If the command is successful, you will receive the following confirmation message:

```
Language 'fr' and its associated lexical entries have been deleted.
```
