Open Translation Engine (OTE)
===
[//]: # ( Open Translation Engine - README.md (markdown) - v0.2.3 )

[![Build Status](https://travis-ci.org/attogram/ote.svg?branch=master)](https://travis-ci.org/attogram/ote)
[![Latest Stable Version](https://poser.pugx.org/attogram/open-translation-engine/v/stable)](https://packagist.org/packages/attogram/open-translation-engine)
[![Latest Unstable Version](https://poser.pugx.org/attogram/open-translation-engine/v/unstable)](https://packagist.org/packages/attogram/open-translation-engine)
[![Total Downloads](https://poser.pugx.org/attogram/open-translation-engine/downloads)](https://packagist.org/packages/attogram/open-translation-engine)
[![License](https://poser.pugx.org/attogram/open-translation-engine/license)](https://github.com/attogram/ote/blob/master/LICENSE.md)
[![Code Climate](https://codeclimate.com/github/attogram/ote/badges/gpa.svg)](https://codeclimate.com/github/attogram/ote)
[![Issue Count](https://codeclimate.com/github/attogram/ote/badges/issue_count.svg)](https://codeclimate.com/github/attogram/ote)
[`[CHANGELOG]`](https://github.com/attogram/ote/blob/master/CHANGELOG.md)
[`[TODO]`](https://github.com/attogram/ote/blob/master/TODO.md)

* The OTE is a collaborative translation dictionary manager for the open content web.

* This is OTE v1.0, under active development, built as an [Attogram Framework](https://github.com/attogram/attogram) module.  It is Dual Licensed under the MIT license *or* the GPL-3 License, at your choosing.

* The "old" stable OTE v0.9.9 is available at: https://github.com/attogram/ote/tree/v0.9.9

* Are you a language lover?  Become a patron of the Attogram Project and help us create translation dictionaries and the software to manage them. https://www.patreon.com/attogram/

* The OTE is **open source**.  OTE source code is dual licensed under the **MIT License** or the **GNU General Public License**, at your choosing.

* The OTE is **open content**. OTE translations are licened under the **Creative Commons Attribution-Share Alike license**, or similar.

* The OTE is under development.  This is version 1.0.*, built with the [Attogram Framework](https://github.com/attogram/attogram).

* @GitHub: https://github.com/attogram/ote

* Get open content dictionary files: https://github.com/attogram/DAMS

Install OTE via composer
===
* INSTALL the Attogram Framework: `composer create-project attogram/attogram-framework your-install-dir`
* GOTO the Attogram modules directory: `cd your-install-dir/modules`
* INSTALL the Open Translation Engine module: `composer create-project attogram/open-translation-engine ote`
* SET your web server to load `your-install-dir/public/` as the site webroot.
* TEST basic setup by loading `check.php` in a web browser.   
* If check.php reports errors with .htaccess, EDIT `public/.htaccess`, set FallbackResource and ErrorDocument's to the full web path to your `public/index.php`
* COPY `public/config.sample.php` to `public/config.php` and edit your site options.
* make sure the database directory `your-install-dir/db` is WRITEABLE by the web server.

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
* make sure the database directory `your-install-dir/db` is WRITEABLE by the web server.
