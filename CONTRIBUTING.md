# Contributing to Open Translation Engine (OTE) v2

First off, thank you for considering contributing to OTE v2. It's people like you that make open source such a great community.

## How Can I Contribute?

### Reporting Bugs

If you find a bug, please open an issue on our [GitHub Issues](https://github.com/attogram/ote/issues) page. Please include as much detail as possible, including:
- A clear and descriptive title.
- A description of the problem.
- Steps to reproduce the bug.
- Any relevant screenshots or error messages.

### Suggesting Enhancements

If you have an idea for a new feature or an enhancement to an existing one, please open an issue on our [GitHub Issues](https://github.com/attogram/ote/issues) page. Please provide a clear and detailed explanation of the feature you're suggesting and why it would be valuable.

### Your First Code Contribution

Unsure where to begin contributing to OTE v2? You can start by looking through the `good-first-issue` and `help-wanted` issues.

## Development Workflow

1.  **Fork the repository** on GitHub.
2.  **Clone your fork** to your local machine.
3.  **Create a new branch** for your changes: `git checkout -b your-branch-name`.
4.  **Make your changes.**
5.  **Run the tests** to make sure everything is still working: `composer test`.
6.  **Run the code formatter** to ensure your code follows our style guide: `composer format`.
7.  **Run the static analyzer** to check for potential bugs: `composer analyse`.
8.  **Commit your changes** with a clear and descriptive commit message.
9.  **Push your changes** to your fork: `git push origin your-branch-name`.
10. **Open a pull request** to the `master` branch of the main repository.

## Coding Standards

This project uses [Laravel Pint](https://laravel.com/docs/pint) to enforce a consistent coding style. Before you commit your changes, please run the code formatter:

```bash
composer format
```

## Running Tests

This project uses [Pest](https://pestphp.com/) for testing. To run the test suite, use the following command:

```bash
composer test
```

## Static Analysis

This project uses [PHPStan](https://phpstan.org/) with the [Larastan](https://github.com/larastan/larastan) extension for static analysis. To run the static analyzer, use the following command:

```bash
composer analyse
```

Thank you for your contribution!
