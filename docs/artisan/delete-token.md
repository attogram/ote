# `ote:delete-token`

The `ote:delete-token` command is used to delete a token and all of its associated data from the system.

## Usage

```bash
php artisan ote:delete-token {token}
```

## Arguments

-   `{token}`: The text of the token you want to delete.

## Description

This is a destructive command that permanently removes a token from the database. When a token is deleted, all lexical entries associated with that token are also deleted, along with their attributes and links. This action cannot be undone.

If a token with the specified text does not exist, the command will show an error message.

## Example

To delete the token "obsolete" from the system, you would run:

```bash
php artisan ote:delete-token obsolete
```

If the command is successful, you will receive the following confirmation message:

```
Token 'obsolete' and its associated lexical entries have been deleted.
```
