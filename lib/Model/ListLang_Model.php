<?php

namespace SmWeb;

class ListLang_Model implements Model {
	private static $instance;
	public static $template;
	public $database;
	public function __construct(database $database) {
		$this->database = $database;
	}
	public static function init() {
		Core::loadClass ( 'Database' );
		self::$template = array (
				'lang_id' => '',
				'lang_name' => '' 
		);
	}
	public static function getInstance(database $database) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
		}
		return self::$instance;
	}
	
	/**
	 * Return a list of all languages in the database
	 */
	public function listAll() {
		$query = "SELECT * FROM {TABLE}listlang ORDER BY lang_name;";
		if (! $res = $this->database->retrieve ( $query, 0 )) {
			return false;
		}
		
		$ret = array ();
		while ( $row = $this->database->get_row ( $res ) ) {
			$ret [] = self::fromRow ( $row );
		}
		return $ret;
	}
	
	/**
	 * Get a single language from its ID
	 */
	public function get($lang_id) {
		$query = "SELECT * FROM {TABLE}listlang WHERE lang_id ='%s'";
		if (! $row = $this->database->retrieve ( $query, 1, $lang_id )) {
			return false;
		}
		return self::fromRow ( $row );
	}
	private function fromRow($row, $depth = 0) {
		return $this->database->row_from_template ( $row, self::$template );
	}
}
