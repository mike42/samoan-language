<?php

namespace SmWeb;

class ListType_Model implements Model {
	private static $instance;
	public static $template;
	public $database;
	public function __construct(database $database) {
		$this->database = $database;
	}
	public static function getInstance(database $database) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
		}
		return self::$instance;
	}
	public static function init() {
		Core::loadClass ( 'Database' );
		self::$template = array (
				'type_id' => '0',
				'type_abbr' => '',
				'type_name' => '',
				'type_title' => '',
				'type_short' => '' 
		);
	}
	
	/**
	 * Return a list of all word types in the database
	 */
	public function listAll($tags = false) {
		$query = "SELECT * FROM {TABLE}listtype WHERE type_istag =%d ORDER BY type_name;";
		if (! $res = $this->database->retrieve ( $query, 0, $tags ? '1' : '0' )) {
			return false;
		}
		
		$ret = array ();
		while ( $row = $this->database->get_row ( $res ) ) {
			$ret [] = self::fromRow ( $row );
		}
		return $ret;
	}
	
	/**
	 * Get details of a word type using its abbreviation
	 *
	 * @param string $type_short        	
	 */
	public function getByShort($type_short) {
		$query = "SELECT * FROM {TABLE}listtype WHERE type_short ='%s'";
		
		if ($row = $this->database->retrieve ( $query, 1, $type_short )) {
			return self::fromRow ( $row );
		}
		return false;
	}
	
	/**
	 * Get details of a word type using its ID
	 *
	 * @param int $type_id        	
	 */
	public function get($type_id) {
		$query = "SELECT * FROM {TABLE}listtype WHERE type_id =%d";
		if (! $row = $this->database->retrieve ( $query, 1, $type_id )) {
			return false;
		}
		return self::fromRow ( $row );
	}
	private function fromRow($row, $depth = 0) {
		return $this->database->row_from_template ( $row, self::$template );
	}
}
