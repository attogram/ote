# `ote:delete-attribute`

The `ote:delete-attribute` command deletes an existing attribute from the database.

## Usage

```bash
php artisan ote:delete-attribute {id}
```

## Arguments

-   `{id}`: The ID of the attribute to be deleted.

## Description

This command is used to permanently remove an attribute from a lexical entry. To use this command, you need to know the specific ID of the attribute you wish to delete. You can find the attribute ID by using the `ote:list-attributes` command.

If an attribute with the specified ID is not found, the command will output an error message.

## Example

To delete the attribute with an ID of `456`, you would run the following command:

```bash
php artisan ote:delete-attribute 456
```

If the command is successful, you will see the following output:

```
Attribute with ID '456' has been deleted.
```
