<?php

namespace SmWeb;

class ListLang_Model implements Model {
	public static $template;
	public static $database;
	public static function init() {
		Core::loadClass ( 'Database' );
		self::$template = array (
				'lang_id' => '',
				'lang_name' => '' 
		);
		self::$database = Database::getInstance();
	}
	
	/**
	 * Return a list of all languages in the database
	 */
	public static function listAll() {
		$query = "SELECT * FROM {TABLE}listlang ORDER BY lang_name;";
		if (! $res = self::$database -> retrieve ( $query, 0 )) {
			return false;
		}
		
		$ret = array ();
		while ( $row = self::$database -> get_row ( $res ) ) {
			$ret [] = self::fromRow ( $row );
		}
		return $ret;
	}
	
	/**
	 * Get a single language from its ID
	 */
	public static function get($lang_id) {
		$query = "SELECT * FROM {TABLE}listlang WHERE lang_id ='%s'";
		if (! $row = self::$database -> retrieve ( $query, 1, $lang_id )) {
			return false;
		}
		return self::fromRow ( $row );
	}
	private static function fromRow($row, $depth = 0) {
		return self::$database -> row_from_template ( $row, self::$template );
	}
}
