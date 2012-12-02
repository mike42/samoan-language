<?php
class def_model {
	private static $template;

	public static function init() {
		core::loadClass('database');
		core::loadClass('listtype_model');
		core::loadClass('example_model');
		
		self::$template = array(
				'def_id'		=> '0',
				'def_entry_id'	=> '0',
				'def_type'		=> '0',
				'def_en'		=> '',
				'rel_type'		=> listtype_model::$template,
				'rel_example'	=> array());
	}
	
	public static function get($word_id, $def_id) {
		$query = "SELECT * FROM sm_def " .
				"LEFT JOIN {TABLE}listtype ON def_type = type_id " .
				"WHERE def_word_id =%d AND def_id =%d;";
		if(!$res = database::retrieve($query, 0, (int)$word_id, (int)$def_id)) {
			return false;
		}
		
		if($row = database::get_row($res)) {
			$def = database::row_from_template($row, self::$template);
			$def['rel_type'] = database::row_from_template($row, listtype_model::$template);
			$def['rel_example'] = example_model::listByDef($def['def_id']);
			return $def;
		}
		
		return false;
	}
	
	public static function listByWord($word_id) {
		$query = "SELECT * FROM sm_def " .
					"LEFT JOIN {TABLE}listtype ON def_type = type_id " .
					"WHERE def_word_id =%d;";
		
		$ret = array();
		if($res = database::retrieve($query, 0, (int)$word_id)) {
			while($row = database::get_row($res)) {
				/* Load def and associated type */
				$def = database::row_from_template($row, self::$template);
				$def['rel_type'] = database::row_from_template($row, listtype_model::$template);
				$def['rel_example'] = example_model::listByDef($def['def_id']);
				$ret[] = $def;
			}
		}
		return $ret;
	}
}
?>