<?php
class def_model {
	private static $template;

	public static function init() {
		core::loadClass('database');
		core::loadClass('listtype_model');
		core::loadClass('example_model');
		core::loadClass('examplerel_model');

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
				"LEFT JOIN sm_listtype ON def_type = type_id " .
				"WHERE def_word_id =? AND def_id =?;";
		if(!$res = database::retrieve($query, [(int)$word_id, (int)$def_id])) {
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
				"LEFT JOIN sm_listtype ON def_type = type_id " .
				"WHERE def_word_id =?;";

		$ret = array();
		if($res = database::retrieve($query, [(int)$word_id])) {
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

	/**
	 * Return ID of a new (blank) definition for a given word
	 * @param int $word_id
	 */
	public static function add($word_id) {
		$query = "INSERT INTO  sm_def (def_id, def_word_id, def_type, def_en) VALUES (NULL, ?,  '0',  '');";
		return database::insert($query, [(int)$word_id]);
	}

	public static function update($def) {
		$query = "UPDATE sm_def SET def_type =?, def_en =? WHERE def_id =?;";
		return database::retrieve($query, [(int)$def['def_type'], $def['def_en'], (int)$def['def_id']]);
	}

	/**
	 * Delete the specified definition
	 *
	 * @param int $def_id
	 */
	public static function delete($def_id) {
		/* Delete linked examples */
		$query = "DELETE FROM sm_examplerel WHERE example_rel_def_id =?;";
		database::retrieve($query, [(int)$def_id]);

		/* Delete definition itself */
		$query = "DELETE FROM sm_def WHERE def_id =?;";
		return database::retrieve($query, [(int)$def_id]);
	}
}

