#!/usr/bin/env php
<?php
/* This script archives SQL of the database (vocab only at the moment) in the data/sql public directory
	- This should be run as a scheduled job (eg by cron).
	- Requires mysqldump, mkdir, gzip, md5sum (any respectable server has this!)
	- .my.cnf must be writeable by the user that runs this script
*/
require_once(dirname(__FILE__) . "/../../api/config.php");

/* Write defaults file to avoid specifying login details on command-line (more of a security problem on shared hosting) */
$db = $config['database'];
$defaults_file = dirname(__FILE__) . "/.my.cnf";
$df = "[mysqldump]\nuser=".$db['user']."\npassword=".$db['password']."\n";
file_put_contents($defaults_file, $df);

/* Make directory */
$date = date("Y-m-d");
$destFull = dirname(__FILE__) . "/../../data/sql/sm-vocabulary-$date.sql";
$cmd = sprintf("mkdir -p %s", escapeshellarg(dirname($destFull)));
exec($cmd);

/* Construct command */
$host = $db['host'];
$defaults = $defaults_file;
$dbname = $db['name'];
$tblStart = $db['name'].".".$db['prefix'];
$tblUser = $tblStart."user";
$tblPage = $tblStart."page";
$tblRevision = $tblStart."revision";
$cmd_template = "mysqldump --defaults-file=%s --host=%s --skip-comments %s --ignore-table=%s --ignore-table=%s --ignore-table=%s > %s";
$cmd = sprintf($cmd_template, escapeshellarg($defaults), escapeshellarg($host), escapeshellarg($dbname), escapeshellarg($tblUser), escapeshellarg($tblPage), escapeshellarg($tblRevision), escapeshellarg($destFull));
exec($cmd);
echo $cmd;
unlink($defaults_file);

/* Compress */
$cmd = sprintf("gzip %s", escapeshellarg($destFull));
exec($cmd);
$destFull .= ".gz";

/* Add checksum */
$cmd = sprintf("md5sum %s > %s", escapeshellarg($destFull), escapeshellarg($destFull.".md5.txt"));
exec($cmd);

?>
