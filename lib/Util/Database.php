<?php

namespace SmWeb;

class Database {
	private static $instance = null;
	private $conn; /* Database connection */
	private static $conf; /* Config */
	public static function init() {
		Database::$conf = Core::getConfig ( "Database" );
	}
	private function __construct() {
		/* Get configuration for this class and connect to the database */
		if (! $this->connect ()) {
			throw new InternalServerErrorException ( "Failed to connect to database: " . mysql_error () );
		}
	}
	private function connect() {
		if (! $this->conn = mysql_connect ( Database::$conf ['host'], Database::$conf ['user'], Database::$conf ['password'] )) {
			return false;
		}
		return mysql_select_db ( Database::$conf ['name'] );
	}
	private function query($query) {
		return mysql_query ( $query, $this->conn );
	}
	public function get_row($result) {
		if ($result == false) {
			return false;
		} else {
			return mysql_fetch_array ( $result );
		}
	}
	private function escape($str) {
		return mysql_real_escape_string ( $str, $this->conn );
	}
	public function insert_id() {
		return mysql_insert_id ( $this->conn );
	}
	public function close() {
		/* Close connection */
		return mysql_close ( $this->conn );
	}
	public function retrieve($query, $return_type = 0, $a1 = null, $a2 = null, $a3 = null, $a4 = null, $a5 = null, $a6 = null, $a7 = null, $a8 = null, $a9 = null, $a10 = null, $a11 = null, $a12 = null, $a13 = null, $a14 = null, $a15 = null) {
		/* Query wrapper to be sure everything is escaped. All SQL must go through here! */
		$query = str_replace ( "{TABLE}", Database::$conf ['prefix'], $query );
		$query = sprintf ( $query, $this->retrieve_arg ( $a1 ), $this->retrieve_arg ( $a2 ), $this->retrieve_arg ( $a3 ), $this->retrieve_arg ( $a4 ), $this->retrieve_arg ( $a5 ), $this->retrieve_arg ( $a6 ), $this->retrieve_arg ( $a7 ), $this->retrieve_arg ( $a8 ), $this->retrieve_arg ( $a9 ), $this->retrieve_arg ( $a10 ), $this->retrieve_arg ( $a11 ), $this->retrieve_arg ( $a12 ), $this->retrieve_arg ( $a13 ), $this->retrieve_arg ( $a14 ), $this->retrieve_arg ( $a15 ) );
		
		$res = $this->query ( $query );
		
		/* Die on database errors */
		if (! $res) {
			$errmsg = 'Query failed:' . $query . " " . mysql_error ();
			throw new InternalServerErrorException ( $errmsg );
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
	public function retrieve_arg($arg) {
		/* Escape an argument for an SQL query, or return false if there was none */
		if ($arg) {
			return $this->escape ( $arg, $this->conn );
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
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Database ();
		}
		return self::$instance;
	}
}
