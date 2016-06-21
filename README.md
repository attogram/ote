Open Translation Engine (OTE)
===
[//]: # ( Open Translation Engine - README.md (markdown) - v0.1.4 )

[![OTE Introduction](https://raw.githubusercontent.com/attogram/ote-docs/master/screenshots/OTE.intro.small.png "OTE Homepage")](https://raw.githubusercontent.com/attogram/ote-docs/master/screenshots/OTE.intro.png)

* The OTE is a **translation dictionary manager** for the web. [Find out more about The OTE](ote/actions/about.md)
* This is OTE v1.0, under active development, built as an [Attogram Framework](https://github.com/attogram/attogram) module.  It is Dual Licensed under the MIT license *or* the GPL-3 License, at your choosing.
* The "old" stable OTE v0.9.9 is available at: https://github.com/attogram/ote/tree/v0.9.9
* Are you a language lover?  Become a patron of the Attogram Project and help us create translation dictionaries and the software to manage them. https://www.patreon.com/attogram/



Install OTE via composer
===
* INSTALL the Attogram Framework: `composer create-project attogram/attogram-framework your-install-dir`
* GOTO the Attogram modules directory: `cd your-install-dir/modules`
* INSTALL the Open Translation Engine module: `composer create-project attogram/open-translation-engine ote`
* SET your web server to load `your-install-dir/public/` as the site webroot.
* TEST basic setup by loading `check.php` in a web browser.   
* If check.php reports errors with .htaccess, EDIT `public/.htaccess`, set FallbackResource and ErrorDocument's to the full web path to your `public/index.php`
* COPY `public/config.sample.php` to `public/config.php` and edit your site options.

Install OTE manually
===
* DOWNLOAD Attogram Framework: https://github.com/attogram/attogram/archive/master.zip
* UNZIP, this creates an `attogram-master/` directory with all the Attogram goodness inside.  Rename as desired.
* Download Attogram Vendor package: https://github.com/attogram/attogram-vendor/archive/master.zip
* UNZIP, then MOVE the `vendor/` directory to the top level of your attogram directory.
* Download Open Translation Engine module:  https://github.com/attogram/ote/archive/master.zip
* GOTO the `modules/` directory, then UNZIP. This creates the `modules/ote-master/` directory.  Rename as desired.
* SET your web server to load the attogram `public/` as the site webroot.
* TEST basic setup by loading `check.php` in a web browser.   
* If check.php reports errors with .htaccess, EDIT `public/.htaccess`, set FallbackResource and ErrorDocument's to the full web path to your `public/index.php`
* COPY `public/config.sample.php` to `public/config.php` and edit your site options.

Misc
===
* Search Page example

[![OTE Search Page](https://raw.githubusercontent.com/attogram/ote-docs/master/screenshots/OTE.search.small.png "OTE Homepage")](https://raw.githubusercontent.com/attogram/ote-docs/master/screenshots/OTE.search.png)

* Words list example

[![OTE Words list](https://raw.githubusercontent.com/attogram/ote-docs/master/screenshots/OTE.words.small.png "OTE Homepage")](https://raw.githubusercontent.com/attogram/ote-docs/master/screenshots/OTE.words.png)
