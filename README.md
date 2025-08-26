# Open Translation Engine (OTE) v2

[![Run Tests](https://github.com/attogram/ote/actions/workflows/tests.yml/badge.svg)](https://github.com/attogram/ote/actions/workflows/tests.yml)

This is the development repository for the **Open Translation Engine (OTE) v2**, a collaborative translation dictionary manager. This project is currently a work-in-progress.

## OTE v2 Development

OTE v2 is being built with [Laravel](https://laravel.com/) and is under active development. The goal is to create a modern, robust, and easy-to-use translation engine.

For a detailed, step-by-step guide on the MVP build process, please see [OTE2.md](OTE2.md).

### Getting Started with OTE v2 (master branch)

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

### Deployment

This project is configured for automated deployment on [Render](https://render.com/). For detailed instructions on how to deploy your own instance, please see the [Render Deployment Guide](docs/RENDER.md).

### OTE v2 TODO

The following is a summary of the planned features for OTE v2. For a more detailed list, see [docs/todo.md](docs/todo.md).

*   **Architecture:** Laravel, PHP >= 7.1.3, support for multiple databases.
*   **Features:** Anonymous use, user authentication, user levels.
*   **Public Features:** Language and dictionary lists, browsing, exporting, searching, and more.
*   **Editor Features:** Word and word pair management, imports.
*   **Admin Features:** Language and user management.

## OTE v1

The previous version of OTE is still available.

*   The last stable release is **OTE v0.9.9**: [v0.9.9 branch](https://github.com/attogram/ote/tree/v0.9.9)
*   OTE Version 1 was a test with the Attogram Framework: [v1 branch](https://github.com/attogram/ote/tree/v1)

### Known Installations of OTE v1

*   <http://ote.2meta.com/>
*   <http://indo-european.info/dictionary-translator/>
*   <http://indo-european.info/translator-dictionary/>
*   <http://indogermanisch.org/woerterbuch-uebersetzer/>
*   <http://www.elas.sk/lehota/slovnik/>
*   <http://fenry.lescigales.org/ryzom/otr/>
*   <http://indo-european.info/pokorny-etymology-dictionary/>
*   <http://dictionar.poezie.ro/>

### Related Projects

*   <https://github.com/elexis-eu/lexonomy>
*   <http://www.omegawiki.org/>
*   <https://github.com/glosswordteam/Glossword>

## Citations

Multilingual Online Resources for Minority Languages of a Campus Community

*   Nur Asmaa Adila Mohamad et al. / Procedia - Social and Behavioral Sciences 27 ( 2011 ) 291 – 298
*   <https://www.sciencedirect.com/science/article/pii/S1877042811024372>
*   <https://doi.org/10.1016/j.sbspro.2011.10.610>
*   "In developing this prototype multilingual dictionary, the available features in OTE 0.9.8 are of great
    help to get started. At the same time there are some weaknesses that can be improved ..."

## License

The Open Translation Engine is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
