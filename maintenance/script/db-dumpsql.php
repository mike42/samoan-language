#!/usr/bin/env php
<?php
/* This script archives SQL of the database (vocab only at the moment) in the data/sql public directory
	- This should be run as a scheduled job (eg by cron).
	- Requires mysqldump, mkdir, gzip, md5sum (any respectable server has this!)
	- .my.cnf must be writeable by the user that runs this script
*/
require_once(dirname(__FILE__) . "/../../api/config.php");
$db = $config['database'];

/* Info used in many places */
$defaults_file = dirname(__FILE__) . "/.my.cnf";
$date = date("Y-m-d");
$destFull = dirname(__FILE__) . "/../../data/sql/sm-vocabulary-$date.sql";
$destFullGz = $destFull . ".gz";

if($argc == 2) { // Pass commands back to bash script, for environments where exec() is unavailable
	switch($argv[1]) {
		case "--write-defaults":
			write_defaults($db, $defaults_file);
			break;
		case "--dump-cmd":
			echo dump_cmd($db, $defaults_file, $destFull);
			break;
		case "--erase-defaults":
			erase_defaults($defaults_file);
			break;
		case "--compress-cmd":
			echo compress_cmd($destFull);	
			break;
		case "--checksum-cmd":
			echo checksum_cmd($destFullGz);
			break;
	default:
		die("Unknown command");
	}
} else { // Below assumes exec() works.
	/* Write defaults */
	write_defaults($db, $defaults_file);

	/* Dump database */
	$dumpCmd = dump_cmd($db, $defaults_file, $destFull);
	exec($dumpCmd);

	/* Erase defaults */
	erase_defaults($defaults_file);

	/* Compress */
	$compressCmd = compress_cmd($destFull);
	exec($compressCmd);

	/* Make checksum file */
	$checksumCmd = checksum_cmd($destFullGz);
	exec($checksumCmd);
}

function write_defaults($db, $defaults_file = "") {
	/* Write defaults file to avoid specifying login details on command-line (more of a security problem on shared hosting) */
	$df = "[mysqldump]\nuser=".$db['user']."\npassword=".$db['password']."\n";
	file_put_contents($defaults_file, $df);
}

function erase_defaults($defaults_file = "") {
	unlink($defaults_file);
}

function dump_cmd($db, $defaults_file = "", $destFull = "") {
	/* Make directory and return command */
	@mkdir(dirname($destFull));

	/* Construct command */
	$host = $db['host'];
	$defaults = $defaults_file;
	$dbname = $db['name'];
	$tblStart = $db['name'].".".$db['prefix'];
	$tblUser = $tblStart."user";
	$tblPage = $tblStart."page";
	$tblRevision = $tblStart."revision";
	$tblLetter = $tblStart."letter";
	$cmd_template = "mysqldump --defaults-file=%s --host=%s --skip-comments %s --ignore-table=%s --ignore-table=%s --ignore-table=%s --ignore-table=%s > %s";
	$cmd = sprintf($cmd_template, escapeshellarg($defaults), escapeshellarg($host), escapeshellarg($dbname), escapeshellarg($tblUser), escapeshellarg($tblPage), escapeshellarg($tblRevision), escapeshellarg($tblLetter), escapeshellarg($destFull));
	return $cmd;
}

function compress_cmd($destFull) {
	/* Command to compress */
	$cmd = sprintf("gzip -f %s", escapeshellarg($destFull));
	return $cmd;
}

function checksum_cmd($destFullGz) {
	/* Command to add md5 checksum */
	$cmd = sprintf("md5sum %s > %s", escapeshellarg($destFullGz), escapeshellarg($destFullGz.".md5.txt"));
	return $cmd;
}

?>
