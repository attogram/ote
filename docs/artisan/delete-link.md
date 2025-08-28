# `ote:delete-link`

The `ote:delete-link` command is used to remove a relationship between two lexical entries.

## Usage

```bash
php artisan ote:delete-link {id}
```

## Arguments

-   `{id}`: The ID of the link to be deleted.

## Description

This command permanently deletes a link between two lexical entries. This is useful if a link was created in error or is no longer valid. To use this command, you need to know the specific ID of the link you want to remove. You can find link IDs by using the `ote:list-links` command.

If a link with the specified ID is not found, the command will output an error message.

## Example

To delete the link with the ID `10`, you would run the following command:

```bash
php artisan ote:delete-link 10
```

Upon successful deletion, you will see a confirmation message:

```
Link with ID '10' has been deleted.
```
