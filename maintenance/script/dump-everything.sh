#!/bin/bash
set -eu

cd database-dumps

echo "Exporting SQL .. "
./db-dumpsql.sh

echo "Exporting raw word list .. "
./db-dumpwordlist.sh

echo "Exporting XDXF .. "
./db-dumpxdxf.sh

