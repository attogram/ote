# Open Translation Engine

This is the **v2-laravel** development branch.

## Dev Install

* ``git clone https://github.com/attogram/ote.git ote``
* ``cd ote``
* ``git checkout v2-laravel``
* ``cp .env.example .env``
* create empty sqlite database
  * ``touch database/database.sqlite`` on unix, or
  * ``type nul > database\database.sqlite`` on windows
* ``composer install``
* ``php artisan key:generate``
* ``php artisan migrate``
* setup webserver:
  * point your webserver to ``ote/public/`` directory, or
  * development server: ``php artisan serve`` and load <http://127.0.0.1:8000>
