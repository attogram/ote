# `ote:update-language`

The `ote:update-language` command is used to update the name of an existing language.

## Usage

```bash
php artisan ote:update-language {id} {new_name}
```

## Arguments

-   `{id}`: The ID of the language you want to update.
-   `{new_name}`: The new name you want to assign to the language.

## Description

This command allows you to change the display name of a language. The language's unique `code` cannot be changed with this command. To find the ID of the language you wish to update, you can use the `ote:list-languages` command.

If a language with the specified ID is not found, the command will display an error message.

## Example

If you want to change the name of the language with ID `1` from "English" to "English (US)", you would run:

```bash
php artisan ote:update-language 1 "English (US)"
```

Upon successful update, you will see a confirmation message:

```
Language with ID '1' has been updated to 'English (US)'.
```
