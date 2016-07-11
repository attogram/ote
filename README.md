Open Translation Engine (OTE)
===
[//]: # ( Open Translation Engine - README.md - v0.3.0 )

[![Build Status](https://travis-ci.org/attogram/ote.svg?branch=master)](https://travis-ci.org/attogram/ote)
[![Latest Stable Version](https://poser.pugx.org/attogram/open-translation-engine/v/stable)](https://packagist.org/packages/attogram/open-translation-engine)
[![Latest Unstable Version](https://poser.pugx.org/attogram/open-translation-engine/v/unstable)](https://packagist.org/packages/attogram/open-translation-engine)
[![Total Downloads](https://poser.pugx.org/attogram/open-translation-engine/downloads)](https://packagist.org/packages/attogram/open-translation-engine)
[![License](https://poser.pugx.org/attogram/open-translation-engine/license)](https://github.com/attogram/ote/blob/master/LICENSE.md)
[![Code Climate](https://codeclimate.com/github/attogram/ote/badges/gpa.svg)](https://codeclimate.com/github/attogram/ote)
[![Issue Count](https://codeclimate.com/github/attogram/ote/badges/issue_count.svg)](https://codeclimate.com/github/attogram/ote)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a078450b69e84d9e8a85232f22b5c5ef)](https://www.codacy.com/app/attogram-project/ote?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=attogram/ote&amp;utm_campaign=Badge_Grade)
[`[CHANGELOG]`](https://github.com/attogram/ote/blob/master/CHANGELOG.md)
[`[TODO]`](https://github.com/attogram/ote/blob/master/TODO.md)

* The OTE is a collaborative translation dictionary manager for the open content web.

* This is OTE v1.\*, under active development, built as an [Attogram Framework](https://github.com/attogram/attogram) module.

* The "old" stable OTE v0.9.\* is available at: https://github.com/attogram/ote/tree/v0.9.9

* Are you a language lover?  Become a patron of the Attogram Project and help us create translation dictionaries and the software to manage them. https://www.patreon.com/attogram/

* The OTE is **open source**.  OTE source code is dual licensed under the **MIT License** or the **GNU General Public License**, at your choosing.

* OTE @ GitHub: https://github.com/attogram/ote

* Get open content dictionary files: https://github.com/attogram/DAMS

Install OTE via composer
===
* INSTALL the Attogram Framework: `composer create-project attogram/attogram-framework your-install-dir`
* GOTO the top level of your new Attogram installation: `cd your-install-dir`
* INSTALL the Attogram Database module: `composer create-project attogram/attogram-database modules/_attogram_db`
* INSTALL the Attogram User module: `composer create-project attogram/attogram-user modules/_attogram_user`
* INSTALL the Open Translation Engine module: `composer create-project attogram/open-translation-engine modules/ote`
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
* GOTO the `modules/` directory, then UNZIP. This INSTALLS the `modules/ote-master/` directory.  Rename as desired.
* DOWNLOAD and INSTALL the Attogram Database module: https://github.com/attogram/attogram-database/archive/master.zip
* DOWNLOAD and INStALL the Attogram User module: https://github.com/attogram/attogram-user/archive/master.zip
* SET your web server to load the attogram `public/` as the site webroot.
* TEST basic setup by loading `check.php` in a web browser.   
* If check.php reports errors with .htaccess, EDIT `public/.htaccess`, set FallbackResource and ErrorDocument's to the full web path to your `public/index.php`
* COPY `public/config.sample.php` to `public/config.php` and edit your site options.
* make sure the database directory `your-install-dir/db` is WRITEABLE by the web server.
