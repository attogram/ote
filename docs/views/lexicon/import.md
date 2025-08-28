# Lexicon Import View

The Lexicon Import view provides a web interface for importing data from a classic OTE (v1) formatted file.

**URL:** `/lexicon/import`
**View File:** `resources/views/lexicon/import.blade.php`

## What it is

This page allows users to upload a text file containing word pairs, which will then be parsed and added to the lexicon. It is the web-based equivalent of the `ote:import-ote-file` Artisan command.

## What it does

The view presents a simple file upload form.

1.  **File Input:**
    A file selector allows the user to choose a local file from their computer. The page suggests that the file should be a `.txt` or `.dat` file, which is typical for this format.

2.  **Import Button:**
    Clicking this button submits the form and uploads the selected file to the server for processing.

### Form Submission and Processing

-   The server receives the uploaded file.
-   It reads the file's metadata (comments starting with `#`) to determine the source and target languages and the delimiter.
-   It then processes each line of the file, creating new `Tokens`, `Languages`, `LexicalEntries`, and `Links` as necessary.
-   If the import is successful, the page reloads with a success message.
-   If there are any errors (e.g., file not found, languages not specified in the file), the page reloads with an error message detailing the problem.
-   The form includes CSRF protection and is set to handle `multipart/form-data`, which is required for file uploads.
-   A "Back to Lexicon" link is provided for easy navigation back to the [Lexicon Index View](index.md).
