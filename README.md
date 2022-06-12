Samoan Language Resources (Web App)
======
This is a web app for managing Samoan Language Resources. It is a wiki-like CMS, with many features to support this:
- Multiple users with customisable permissions.
- Embedded audio.
- In-browser editable vocabulary.
- Database of usage examples.
- Pages can embed definitions and usage examples.

Requirements
------------
This project is PHP-based, so it should run on any major OS.

You will require the [imagick](http://php.net/manual/en/book.imagick.php) extension, which is used to generate thumbnails.

Setup
-----
First clone the repo with submodules:

    git clone --recursive https://github.com/mike42/samoan-language

Then follow the instructions in maintenance/help.html for the configuration steps. 

Credit
------
* Uses modified version of [zBench](http://wordpress.org/extend/themes/zbench/developers/) wordpress theme (GPL v2 or newer)
* Uses an [in-house Wikitext parser](http://mike42.me/wikitext/) for a wiki-like feel to the editing interface.
* Uses [jQuery UI](http://jqueryui.com) and [jQuery](http://jquery.com) for some non-static elements.
