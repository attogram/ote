# `ote:add-attribute`

The `ote:add-attribute` command adds a new attribute to a specified lexical entry.

## Usage

```bash
php artisan ote:add-attribute {entry_id} {key} {value}
```

## Arguments

-   `{entry_id}`: The ID of the lexical entry to which the attribute should be added.
-   `{key}`: The key of the attribute (e.g., "pronunciation", "gender").
-   `{value}`: The value of the attribute.

## Description

This command allows you to associate a key-value pair as an attribute to an existing lexical entry. This can be used to add extra information to an entry, such as its pronunciation, grammatical gender, or any other relevant data.

If the specified `entry_id` does not exist, the command will return an error, and no attribute will be created.

## Example

To add a "pronunciation" attribute to the lexical entry with ID `123`, you would run the following command:

```bash
php artisan ote:add-attribute 123 pronunciation /pɹəˌnʌnsiˈeɪʃən/
```

Upon successful execution, you will see a confirmation message:

```
Attribute 'pronunciation' added to entry 123 with ID {new_attribute_id}.
```
