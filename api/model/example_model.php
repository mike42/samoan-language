<?php
class example_model {
	public static $template;

	public static function init() {
		core::loadClass('database');
		
		self::$template = array(
				'example_id'				=> '0',
				'example_str'				=> '',
				'example_t_style'			=> '',
				'example_k_style'			=> '',
				'example_t_style_recorded'	=> '0',
				'example_k_style_recorded'	=> '',
				'example_en'				=> '',
				'example_en_lit'			=> '',
				'example_uploaded'			=> '',
				'example_audio_tag'			=> '');
	}

	/**
	 * Get a single example using example_id
	 * 
	 * @param number $id ID to fetch
	 */
	public static function getById($def_id) {
		$sql = "SELECT * FROM {TABLE}example WHERE example_id ='%d';";
		if($row = database::retrieve($query, 1, (int)$def_id)) {
			return database::row_from_template($row, self::$template);
		}
		return false;
	}
	
	/**
	 * Get all examples associated with a given definition
	 */
	public static function listByDef($def_id) {
		$query = "SELECT * FROM {TABLE}examplerel " .
					"JOIN {TABLE}example ON example_rel_example_id = example_id " .
					"WHERE example_rel_def_id =%d";
		$ret = array();
		if($res = database::retrieve($query, 0, (int)$def_id)) {
			while($row = database::get_row($res)) {
				/* Load examples */
				$example = database::row_from_template($row, self::$template);
				$ret[] = $example;
			}
		}
		return $ret;
	}
	
	/**
	 * Find examples which mention a given word. Use to prompt suggested additions to examples
	 */
	public static function listByWordMention($spelling_t_style, $word_num) {
		$id = word_model::getIdStrBySpellingNum($spelling_t_style, $word_num);
		$sql = "SELECT * FROM {TABLE}example WHERE example_str like '%%[%s|%%' or example_str like '%%[%s]%%';";
		
		$ret = array();
		if($res = database::retrieve($query, 0, $id)) {
			while($row = database::get_row($res)) {
				/* Load examples */
				$example = database::row_from_template($row, self::$template);
				$ret[] = $example;
			}
		}
		return $ret;
	}
}
?>