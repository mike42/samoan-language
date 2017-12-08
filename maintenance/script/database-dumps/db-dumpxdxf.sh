#!/bin/bash
set -eu

# Setup
date=`date --rfc-3339=date`
mkdir -p ../../../data/xdxf/
mkdir -p mike-sm

# Create files
./xdxf-export.php > mike-sm/dict.xdxf
file=../../../data/xdxf/mike-sm-$date.tar.gz
tar --remove-files --gzip --create --file $file mike-sm

# Checksum
cd ../../../data/xdxf/
md5sum mike-sm-$date.tar.gz > mike-sm-$date.tar.gz.md5.txt
