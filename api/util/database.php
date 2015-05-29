<?php

namespace SmWeb;

class database {
	private static $conn; /* Database connection */
	private static $conf; /* Config */
	public static function init() {
		/* Get configuration for this class and connect to the database */
		database::$conf = core::getConfig ( "database" );
		if (! database::connect ()) {
			core::fizzle ( "Failed to connect to database: " . mysql_error () );
		}
	}
	private static function connect() {
		if (! database::$conn = mysql_connect ( database::$conf ['host'], database::$conf ['user'], database::$conf ['password'] )) {
			return false;
		}
		return mysql_select_db ( database::$conf ['name'] );
	}
	private static function query($query) {
		return mysql_query ( $query );
	}
	public static function get_row($result) {
		if ($result == false) {
			return false;
		} else {
			return mysql_fetch_array ( $result );
		}
	}
	public static function escape($str) {
		return mysql_real_escape_string ( $str );
	}
	public function insert_id() {
		return mysql_insert_id ();
	}
	public static function close() {
		/* Close connection */
		return mysql_close ( Database::$conn );
	}
	static function retrieve($query, $return_type = 0, $a1 = null, $a2 = null, $a3 = null, $a4 = null, $a5 = null, $a6 = null, $a7 = null, $a8 = null, $a9 = null, $a10 = null, $a11 = null, $a12 = null, $a13 = null, $a14 = null, $a15 = null) {
		/* Query wrapper to be sure everything is escaped. All SQL must go through here! */
		$query = str_replace ( "{TABLE}", database::$conf ['prefix'], $query );
		$query = sprintf ( $query, Database::retrieve_arg ( $a1 ), Database::retrieve_arg ( $a2 ), Database::retrieve_arg ( $a3 ), Database::retrieve_arg ( $a4 ), Database::retrieve_arg ( $a5 ), Database::retrieve_arg ( $a6 ), Database::retrieve_arg ( $a7 ), Database::retrieve_arg ( $a8 ), Database::retrieve_arg ( $a9 ), Database::retrieve_arg ( $a10 ), Database::retrieve_arg ( $a11 ), Database::retrieve_arg ( $a12 ), Database::retrieve_arg ( $a13 ), Database::retrieve_arg ( $a14 ), Database::retrieve_arg ( $a15 ) );
		
		$res = Database::query ( $query );
		
		/* Die on database errors */
		if (! $res) {
			$errmsg = 'Query failed:' . $query . " " . mysql_error ();
			;
			Core::fizzle ( $errmsg );
		}
		
		/* Return methods: Return a result set, or return a row if only one is expected */
		switch ($return_type) {
			case 0 :
				return $res;
			case 1 :
				return Database::get_row ( $res );
			case 2 :
				return Database::insert_id ( $res );
		}
	}
	function retrieve_arg($arg) {
		/* Escape an argument for an SQL query, or return false if there was none */
		if ($arg) {
			return Database::escape ( $arg );
		}
		return false;
	}
	static function row_from_template($row, $template) {
		/* This copies an associative array from the database, copying only fields which exist in this template */
		$res = $template;
		foreach ( $row as $key => $val ) {
			if (isset ( $res [$key] )) {
				$res [$key] = $val;
			}
		}
		return $res;
	}
}
