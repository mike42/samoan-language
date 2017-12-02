#!/usr/bin/env php
<?php
/* This script will tidy up the database to clean up after word numberings and moves. */
require_once(dirname(__FILE__) . "/../../api/core.php");
core::loadClass("database");

/* Delete spellings which aren't used */
echo "Clearing orphaned spellings ...";
$query = "DELETE sm_spelling FROM sm_spelling LEFT JOIN sm_word ON spelling_id = word_spelling WHERE word_id IS NULL;";
database::delete($query);
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
