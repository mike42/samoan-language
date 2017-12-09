<?php
/**
 *	Wrapper for mySQL extension to manage connection and avoid injection.
 */
class database {
	private static $conn = null; /* Database connection */
	private static $conf = null; /* Config */

	public static function init() {
		/* Get configuration for this class and connect to the database */
		self::$conf = core::getConfig(__CLASS__);
		self::connect();
	}

	private static function connect() {
		self::$conn = new PDO("mysql:host=" . self::$conf['host'] . ";dbname=" . self::$conf['name'] . ";charset=latin1", self::$conf['user'], self::$conf['password']);
		self::$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public static function get_row($result) {
		if(!$result) {
			return false;
		}
		return $result -> fetch(PDO::FETCH_ASSOC);
	}

	public static function insert_id() {
		return self::$conn -> lastInsertId();
	}

	public static function close() {
		/* Close connection */
		self::$conn = null;
		return true;
	}

	public static function retrieve($query, array $arg = []) {
		return self::doQuery($query, $arg);
	}

	public static function insert($query, array $arg = []) {
		$res = self::doQuery($query, $arg);
		return self::insert_id();
	}

	public static function delete($query, array $arg = []) {
		$res = self::doQuery($query, $arg);
		return true;
	}

	public static function update($query, array $arg = []) {
		$res = self::doQuery($query, $arg);
		return true;
	}

	private static function doQuery($query, array $arg) {
		if(!self::$conn) {
			self::init();
		}
		/* Query wrapper to be sure everything is escaped. All SQL must go through here! */
		// Can be un-commented for database query logging
		//error_log("database::doQuery: " . $query . " " . json_encode($arg), 0);
		$stmt = self::$conn -> prepare($query);
		try {
		  $stmt -> execute($arg);
		} catch (PDOException $e) {
		    error_log("database::doQuery(): " . $e, 0);
		    throw new Exception("A database query failed.");
		}
		return $stmt;
	}

	static function row_from_template($row, $template) {
		/* This copies an associative array from the database, copying only fields which exist in this template */
		$res = $template;
		foreach($row as $key => $val) {
			if(isset($res[$key])) {
				$res[$key] = $val;
			}
		}
		return $res;
	}
}

?>
