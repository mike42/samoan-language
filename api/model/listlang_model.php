<?php
class listlang_model {
	public static $template;

	public static function init() {
		core::loadClass('database');
		self::$template = array(
				'lang_id'	=> '',
				'lang_name'	=> '');
	}

	/**
	 * Return a list of all languages in the database
	 */
	public static function listAll() {
		$query = "SELECT * FROM sm_listlang ORDER BY lang_name;";
		if(!$res = database::retrieve($query)) {
			return false;
		}

		$ret = array();
		while($row = database::get_row($res)) {
			$ret[] = self::fromRow($row);
		}
		return $ret;
	}

	/**
	 * Get a single language from its ID
	 */
	public static function get($lang_id) {
		$query = "SELECT * FROM sm_listlang WHERE lang_id =?";
		if(!$row = database::get_row(database::retrieve($query, [$lang_id]))) {
			return false;
		}
		return self::fromRow($row);
	}

	private static function fromRow($row, $depth = 0) {
		return database::row_from_template($row, self::$template);
	}

}



