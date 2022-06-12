# Environment setup

The purpose of this file is to help you set up a local copy of the language database for testing changes. This is not a
general-purpose web app, so we mainly have our own set-up in mind.

Software required - versions are ones which are currently used somewhere:

- MySQL (5.5.24)
- Apache with `mod_rewrite` enabled (2.2.22)
- PHP (5.3.10)

## Installation steps

Copy your files to the webroot of the server

Import shcema. The schema is in this file: `files/samoan-schema.sql`. You may also want to download a dump of the database if you plan on working with definitions.

Make your config.php. Fill in the database section in the blank configuration file (`files/config.php.txt`), and copy it to `api/config.php`.

Set up a `.htaccess` file in the webroot:

If you have put the scripts in the <tt>/sm</tt> folder, and want to access them as `/samoan` then the example (`files/example.htaccess.txt`) will do it.

Make sure you set `AllowOverride All` for the directory so that the `.htaccess` file will be read.

You may also need to change your configuration to load mod_rewrite, if your distribution does not load it by default.
