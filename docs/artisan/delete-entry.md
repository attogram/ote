# `ote:delete-entry`

The `ote:delete-entry` command is used to delete a lexical entry from the database.

## Usage

```bash
php artisan ote:delete-entry {id}
```

## Arguments

-   `{id}`: The ID of the lexical entry to be deleted.

## Description

This command permanently removes a lexical entry and all of its associated data, including any attributes and links connected to it. This action is irreversible.

To find the ID of the lexical entry you wish to delete, you can use the `ote:list-entries` command.

If a lexical entry with the specified ID cannot be found, the command will output an error message.

## Example

To delete the lexical entry with the ID `789`, you would run the following command:

```bash
php artisan ote:delete-entry 789
```

Upon successful deletion, you will see a confirmation message:

```
Lexical entry with ID '789' has been deleted.
```
