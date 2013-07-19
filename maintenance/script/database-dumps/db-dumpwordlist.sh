#!/bin/bash
date=`date --rfc-3339=date`
mkdir -p ../../../data/wordlist/
./list-words.php > ../../../data/wordlist/sm-wordlist-$date.txt
