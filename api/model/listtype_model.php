<?php
class listtype_model {
	public static $template;

	public static function init() {
		core::loadClass('database');
		self::$template = array(
				'type_id'		=> '0',
				'type_abbr'		=> '',
				'type_name' 	=> '',
				'type_title'	=> '',
				'type_short'	=> '');
	}
	
	/**
	 * Return a list of all word types in the database
	 */
	public static function listAll() {
		$query = "SELECT * FROM {TABLE}listtype ORDER BY type_name;";
		if(!$res = database::retrieve($query, 0)) {
			return false;
		}
		
		$ret = array();
		while($row = database::get_row($res)) {
			$ret[] = self::fromRow($row);
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
		
		if($row = database::retrieve($query, 1, $type_short)) {
			return self::fromRow($row);
		}
		return false;
	}
	
	private static function fromRow($row, $depth = 0) {
		return database::row_from_template($row, self::$template);
	}
}

?>