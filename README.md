Samoan Language Web App
=======================
This is a web app for managing Samoan Language Resources. It is a wiki-like CMS, with features targeted to language documentation:

- Multiple users with customisable permissions.
- Embedded audio.
- In-browser editable vocabulary.
- Database of usage examples.
- Pages can embed definitions and usage examples.

Requirements
------------
This project is PHP-based, so it should run on any major OS.

You will require the [imagick](http://php.net/manual/en/book.imagick.php) extension, which is used by the Wikitext parset to generate thumbnails.

Setup
-----
First clone the repo with submodules:

    git clone --recursive https://github.com/mike42/samoan

Then follow the instructions in maintenance/help.html, which will show you how to:

* Create a database loaded with the schema
* Create config
* Set up apache with htaccess.
* Add a user

Development
-----------
For development, you will need:
* yui-compressor
* GNU make
* phpunit
* composer (suggested for Eclipse users)

To re-compile the CSS, run:

    make

To run unit tests, use:

    phpunit tests/

And to generate a HTML code coverage report:

	phpunit --coverage-html coverage/ tests/
    # or..
    make coverage

Credit
------
* Uses modified version of [zBench](https://wordpress.org/themes/zbench/) wordpress theme (GPL v2 or newer)
* Uses the [Bitrevision Wikitext parser](http://mike.bitrevision.com/wikitext/) for a wiki-like feel to the editing interface.
* Uses [jQuery UI](http://jqueryui.com) and [jQuery](http://jquery.com) for some non-static elements.

