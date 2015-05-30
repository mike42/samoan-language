#!/usr/bin/env php
<?php

namespace SmWeb;

/* This script will tidy up the database to clean up after word numberings and moves. */
require_once (dirname ( __FILE__ ) . "/../../lib/core.php");
Core::loadClass ( "Database" );

/* Delete spellings which aren't used */
echo "Clearing orphaned spellings ...";
$query = "DELETE {TABLE}spelling FROM {TABLE}spelling LEFT JOIN {TABLE}word ON spelling_id = word_spelling WHERE word_id IS NULL;";
Database::retrieve ( $query, 0 );
echo " done\n";

/* Spot spellings which are incorrectly numbered:
 * 		SELECT *
FROM (

		SELECT word_spelling, COUNT( word_id ) AS wordCount, MAX( word_num ) AS highestNum
		FROM sm_word
		GROUP BY word_spelling
)a
WHERE highestNum !=0
AND wordCount != highestNum */
