#!/usr/bin/env php
<?php
/* Return raw list of words which are contained in the database,
	in Samoan alphabetical order (if the sortkey is correct, that is!). */
require_once(dirname(__FILE__) . "/../../../api/core.php");
core::loadClass("database");

$query = "SELECT spelling_t_style FROM sm_spelling` WHERE 1 ORDER BY spelling_sortkey_sm;";
$res = database::retrieve($query);
while($row = database::get_row($res)) {
	echo $row[0] . "\n";
}

?>
