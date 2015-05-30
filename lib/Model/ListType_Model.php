<?php

namespace SmWeb;

class ListType_Model implements Model {
	public static $template;
	public static $database;
	public static function init() {
		Core::loadClass ( 'Database' );
		self::$template = array (
				'type_id' => '0',
				'type_abbr' => '',
				'type_name' => '',
				'type_title' => '',
				'type_short' => '' 
		);
		self::$database = Database::getInstance();
	}
	
	/**
	 * Return a list of all word types in the database
	 */
	public static function listAll($tags = false) {
		$query = "SELECT * FROM {TABLE}listtype WHERE type_istag =%d ORDER BY type_name;";
		if (! $res = self::$database -> retrieve ( $query, 0, $tags ? '1' : '0' )) {
			return false;
		}
		
		$ret = array ();
		while ( $row = self::$database -> get_row ( $res ) ) {
			$ret [] = self::fromRow ( $row );
		}
		return $ret;
	}
	
	/**
	 * Get details of a word type using its abbreviation
	 *
	 * @param string $type_short        	
	 */
	public static function getByShort($type_short) {
		$query = "SELECT * FROM {TABLE}listtype WHERE type_short ='%s'";
		
		if ($row = self::$database -> retrieve ( $query, 1, $type_short )) {
			return self::fromRow ( $row );
		}
		return false;
	}
	
	/**
	 * Get details of a word type using its ID
	 *
	 * @param int $type_id        	
	 */
	public static function get($type_id) {
		$query = "SELECT * FROM {TABLE}listtype WHERE type_id =%d";
		if (! $row = self::$database -> retrieve ( $query, 1, $type_id )) {
			return false;
		}
		return self::fromRow ( $row );
	}
	private static function fromRow($row, $depth = 0) {
		return self::$database -> row_from_template ( $row, self::$template );
	}
}
