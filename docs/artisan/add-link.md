# `ote:add-link`

The `ote:add-link` command creates a relationship between two lexical entries.

## Usage

```bash
php artisan ote:add-link {source_id} {target_id} {type}
```

## Arguments

-   `{source_id}`: The ID of the source (origin) lexical entry.
-   `{target_id}`: The ID of the target (destination) lexical entry.
-   `{type}`: The type of relationship between the entries (e.g., "translation", "synonym", "antonym").

## Description

This command is used to link two lexical entries together, creating a semantic relationship between them. This is the core of the translation dictionary, allowing you to define how words and phrases relate to each other.

Both the source and target lexical entries must exist before you can create a link between them. You can find the IDs of the entries using the `ote:list-entries` command.

If a link with the same source, target, and type already exists, the command will not create a duplicate.

## Example

To create a "translation" link from the English entry for "cat" (ID `5`) to the French entry for "chat" (ID `6`), you would run:

```bash
php artisan ote:add-link 5 6 translation
```

If the link is created successfully, you will see a confirmation message:

```
Link created successfully: 5 -> 6 (translation).
```
