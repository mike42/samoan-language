#!/usr/bin/php
<?php
/* Use this script to make a new user, for when there are no users in the database */
if(count($argv) != 4) {
	/* Wrong number of arguments -- Show usage */
	echo "mkuser.php: Create a new user when none exists yet\n";
	echo "Usage:\n";
	echo $argv[0] . " [username] [email] [password]\n";
} else {
	/* Data to use */
	$user_name  = $argv[1];
	$user_email = $argv[2];
	$password   = $argv[3];

	/* Insert user */
	require_once(dirname(__FILE__) . "/../../api/core.php");
	core::loadClass("user_model");
	$id = user_model::insert($user_name, $user_email, $password);

	if(!$id) {
		echo "Inserting the user failed. Check for duplicate accounts or invalid email addresses.\n";
		exit(1);
	}
	echo "User $id inserted.\n";
}
?>
