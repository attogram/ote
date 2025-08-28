# `ote:add-token`

The `ote:add-token` command adds a new, unique token to the system.

## Usage

```bash
php artisan ote:add-token {text}
```

## Arguments

-   `{text}`: The text of the token to be added. This can be a single word or a short phrase.

## Description

This command is used to create a new token. In OTE, a token is a language-agnostic representation of a word or phrase. For example, the token "run" could be linked to the English lexical entry "run", the Spanish entry "correr", and the French entry "courir".

The command ensures that each token's text is unique. If you try to add a token that already exists, the command will not create a duplicate and will instead inform you of the existing token's ID.

## Example

To add the token "example" to the system, you would run the following command:

```bash
php artisan ote:add-token example
```

If the token is new, you will see a success message:

```
Token 'example' added successfully with ID {new_token_id}.
```

If the token already exists, you will see a warning:

```
Token 'example' already exists with ID {existing_token_id}.
```
