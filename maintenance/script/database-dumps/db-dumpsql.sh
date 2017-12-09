#!/bin/bash
set -eu

# Silly trick to avoid using exec() command in PHP
./db-dumpsql.php --write-defaults
bash -c "`./db-dumpsql.php --dump-cmd`"
./db-dumpsql.php --erase-defaults
bash -c "`./db-dumpsql.php --compress-cmd`"
bash -c "`./db-dumpsql.php --checksum-cmd`"
