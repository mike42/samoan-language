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
		$query = "SELECT * FROM {TABLE}listlang ORDER BY lang_name;";
		if(!$res = database::retrieve($query, 0)) {
			return false;
		}

		$ret = array();
		while($row = database::get_row($res)) {
			$ret[] = self::fromRow($row);
		}
		return $ret;
	}
	
	private static function fromRow($row, $depth = 0) {
		return database::row_from_template($row, self::$template);
	}

}



?>