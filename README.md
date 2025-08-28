# Open Translation Engine (OTE)

[![Run Tests](https://github.com/attogram/ote/actions/workflows/tests.yml/badge.svg)](https://github.com/attogram/ote/actions/workflows/tests.yml)
[![Release](https://img.shields.io/github/v/release/attogram/ote?style=flat)](https://github.com/attogram/ote/releases)

[![GitHub stars](https://img.shields.io/github/stars/attogram/ote?style=flat)](https://github.com/attogram/ote/stargazers)
[![GitHub watchers](https://img.shields.io/github/watchers/attogram/ote?style=flat)](https://github.com/attogram/ote/watchers)
[![Forks](https://img.shields.io/github/forks/attogram/ote?style=flat)](https://github.com/attogram/ote/forks)
[![Issues](https://img.shields.io/github/issues/attogram/ote?style=flat)](https://github.com/attogram/ote/issues)
[![GitHub commit activity](https://img.shields.io/github/commit-activity/t/attogram/ote?style=flat)](https://github.com/attogram/ote/commits/main/)
[![License](https://img.shields.io/github/license/attogram/ote?style=flat)](./LICENSE)

Welcome to OTE, the **Open Translation Engine**

a collaborative translation dictionary manager

## History

- 2007-10 - OTE project [started](https://sourceforge.net/projects/ote/) 
  on [SourceForge](https://sourceforge.net/p/ote/code/1/)

- 2007-10 - [ote.2meta.com](https://ote.2meta.com/) started, an OTE installation with Dutch and English.

- 2011-10 - First [academic citation](https://www.sciencedirect.com/science/article/pii/S1877042811024372)

- 2014-09 - OTE project [moved](https://github.com/attogram/ote/tree/f23a6d5c665a740f45960aa380e8ab47d2d301bc)
  to [GitHub](https://github.com/attogram/ote/)

- 2025-08 - OTE v2 development [started](https://github.com/attogram/ote/blob/71edc2a5877bb5e992b12e149e61d437a7357db2/OTE2.md)

## Installations

### Active as of 2025-08

- https://ote.2meta.com/ 
    - Dutch, English 
    - OTE v1.0.0
- https://indogermanisch.org/woerterbuch-uebersetzer/ 
    - Deutsch, Indogermanisch
    - OTE v0.9.8.8
    - (note: https setup currently broken, but site still available)
- http://dictionar.poezie.ro/ 
    - English, Romanian
    - OTE v0.9.7
- https://fenry.lescigales.org/ryzom/otr/ 
    - Atysien, Tryker
    - OTE version unknown

### Inactive as of 2025-08

- https://indo-european.info/dictionary-translator/ (Invalid SSL certificate)
- https://indo-european.info/pokorny-etymology-dictionary/ (Invalid SSL certificate)
- https://indo-european.info/translator-dictionary/ (Invalid SSL certificate)
- https://www.elas.sk/lehota/slovnik/ (Unreachable)

--
- https://attogram.github.io/ote/ - old info site, currently down

## Citations

Multilingual Online Resources for Minority Languages of a Campus Community

*   Nur Asmaa Adila Mohamad et al. / Procedia - Social and Behavioral Sciences 27 ( 2011 ) 291 – 298
*   <https://www.sciencedirect.com/science/article/pii/S1877042811024372>
*   <https://doi.org/10.1016/j.sbspro.2011.10.610>
*   "In developing this prototype multilingual dictionary, the available features in OTE 0.9.8 are of great
    help to get started. At the same time there are some weaknesses that can be improved ..."

### OTE v2

To get started with the development of OTE v2, you will need to have PHP and Composer installed on your system.

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/attogram/ote.git
    cd ote
    ```
2.  **Install dependencies:**
    ```bash
    composer install
    ```
3.  **Create the environment file:**
    ```bash
    cp .env.example .env
    ```
4.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```

### Testing

For information on how to run the test suite, please see the [Testing Documentation](tests/README.md).

### Git Hooks

This project includes a pre-commit hook that runs `pint` and `phpstan` to ensure code quality before each commit. To use it, you need to create a symbolic link from `.git/hooks/pre-commit` to the script.

From the root of the project, run the following command:

```bash
ln -s ../../bin/pre-commit.sh .git/hooks/pre-commit
```

### Deployment

This project is configured for automated deployment on [Render](https://render.com/). For detailed instructions on how to deploy your own instance, please see the [Render Deployment Guide](docs/render.md).

### Development

For information on setting up a development environment, please see the following guides:

-   [GitHub Codespaces](docs/github-codespaces.md)
-   [Docker](docs/docker.md)

### OTE v2 TODO

The following is a summary of the planned features for OTE v2. For a more detailed list, see [docs/todo.md](docs/todo.md).

*   **Architecture:** Laravel, PHP >= 7.1.3, support for multiple databases.
*   **Features:** Anonymous use, user authentication, user levels.
*   **Public Features:** Language and dictionary lists, browsing, exporting, searching, and more.
*   **Editor Features:** Word and word pair management, imports.
*   **Admin Features:** Language and user management.

## Known Issues

### Laravel Dusk

At the time of writing, there are known issues with running Laravel Dusk in some development environments. 
The test runner (Pest) seems to have a conflict with how Dusk's test cases are discovered, 
which can lead to errors. Additionally, there can be issues with the ChromeDriver and Chrome binary setup.

For these reasons, browser testing with Dusk has been temporarily disabled.

## OTE v1

The previous version of OTE is still available.

The last stable release was **OTE v0.9.9**: [v0.9.9 branch](https://github.com/attogram/ote/tree/v0.9.9)

## Related Projects

- https://github.com/elexis-eu/lexonomy
- http://www.omegawiki.org/ (INACTIVE)
- https://github.com/glosswordteam/Glossword>

## License

The Open Translation Engine is open-sourced software licensed under the
[MIT license](https://opensource.org/licenses/MIT).
