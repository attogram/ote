# `ote:update-token`

The `ote:update-token` command is used to update the text of an existing token.

## Usage

```bash
php artisan ote:update-token {id} {new_text}
```

## Arguments

-   `{id}`: The ID of the token you want to update.
-   `{new_text}`: The new text you want to assign to the token.

## Description

This command allows you to change the text of a token. This can be useful for correcting spelling mistakes or for standardizing token formats. To find the ID of the token you wish to update, you can use the `ote:list-tokens` command.

If a token with the specified ID is not found, the command will display an error message.

## Example

If you want to change the text of the token with ID `5` from "color" to "colour", you would run:

```bash
php artisan ote:update-token 5 colour
```

Upon successful update, you will see a confirmation message:

```
Token with ID '5' has been updated to 'colour'.
```
