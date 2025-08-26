# OTE v2 Testing

This document provides instructions on how to run the existing tests and how to write new tests for the OTE v2 project.

## Running Tests

To run all the tests, you can use the following command from the root of the project:

```bash
./vendor/bin/pest
```

Alternatively, you can use the `test` Artisan command (if the environment issues are resolved):

```bash
php artisan test
```

### Running a Single Test File

To run a single test file, you can pass the path to the file to the `pest` command:

```bash
./vendor/bin/pest tests/Feature/TokenControllerTest.php
```

## Writing Tests

### Unit Tests

Unit tests focus on a very small, isolated portion of your code. They should be placed in the `tests/Unit` directory. By default, they do not boot the Laravel application and do not have access to the database.

If a unit test needs to access the database, you can use the `Illuminate\Foundation\Testing\RefreshDatabase` trait.

### Feature Tests

Feature tests may test a larger portion of your code, including how several objects interact with each other or even a full HTTP request. They should be placed in the `tests/Feature` directory. By default, they boot the Laravel application and have access to the database.

### Creating New Tests

When creating a new test, it's recommended to use the `make:test` Artisan command (if available):

```bash
# Create a new feature test
php artisan make:test MyNewFeatureTest

# Create a new unit test
php artisan make:test MyNewUnitTest --unit
```

If `artisan` is not available, you can create the test file manually in the appropriate directory.

## Test Results Log

After running the test suite, you can generate test result logs in various formats. The generated files will be placed in the root of the repository and should be committed.

### Plain Text Log

To generate a plain text log file (`test_results.log`), you can use the following Composer script:

```bash
composer test:log
```

### Other Formats

You can also generate test results in other formats for consumption by other tools or for easier reading. The following commands are available:

-   `composer test:log:junit`: Generates a JUnit XML file (`test_results.xml`).
-   `composer test:log:testdox-html`: Generates a TestDox HTML file (`test_results.html`).
-   `composer test:log:testdox-text`: Generates a TestDox plain text file (`test_results.txt`).
-   `composer test:log:teamcity`: Generates a TeamCity log file (`test_results.teamcity.txt`).

### All Formats

To generate all the available log files at once, you can use the following command:

```bash
composer test:log:all
```
