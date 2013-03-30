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
	public static function getById($example_id) {
		$sql = "SELECT * FROM {TABLE}example WHERE example_id ='%s';";
		if($row = database::retrieve($sql, 1, (int)$example_id)) {
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
		$query = "SELECT * FROM {TABLE}example WHERE example_str like '%%[%s|%%' or example_str like '%%[%s]%%';";
		$ret = array();
		if($res = database::retrieve($query, 0, $id, $id)) {
			while($row = database::get_row($res)) {
				/* Load examples */
				$example = database::row_from_template($row, self::$template);
				$ret[] = $example;
			}
		}
		return $ret;
	}
	
	/**
	 * Create a new example and return the ID
	 */
	public static function insert($example_sm, $example_en) {
		$str = self::autobracket($example_sm);
		$query = "INSERT INTO {TABLE}example (example_id, example_str, example_t_style, example_k_style, example_t_style_recorded, example_k_style_recorded, example_en, example_en_lit, example_uploaded, example_audio_tag) VALUES (NULL ,  '%s',  '%s',  '%s',  '%d',  '%d', '%s', '%s', CURRENT_TIMESTAMP, '%s');";
		$id = database::retrieve($query, 2, $str, $example_sm, '', '0', '0', $example_en, '', '');
		return $id;
	}
	
	/* Wrap each word in single-brackets */
	private static function autobracket($str) {
		$a = explode(" ", $str);
		$i = 0;
		foreach($a as $b) {
			$a[$i] = "[".$a[$i]."]";
			$i++;
		}
		return join(" ", $a);
	}

	/**
	 * @return number Total number of examples currently stored.
	 */
	public static function countExamples() {
		$query = "SELECT COUNT(example_id) FROM  {TABLE}example;";
		if($row = database::retrieve($query, 1)) {
			return (int)$row[0];
		}
		return 0;
	}

	public static function update($example) {
		$query = "UPDATE {TABLE}example SET example_str ='%s', example_en='%s' WHERE example_id =%d";
		database::retrieve($query, 0, $example['example_str'], $example['example_en'], (int)$example['example_id']);
	}

	public static function delete($example_id) {
		/* Delete an example, after removing it from everywhere it appears */
		$query = "DELETE FROM {TABLE}exampleaudio WHERE example_id =%d;";
		database::retrieve($query, 0, (int)$example_id); // NB: this may leave orphan audio files.

		$query = "DELETE FROM {TABLE}examplerel WHERE example_rel_example_id =%d;";
		database::retrieve($query, 0, (int)$example_id);

		$query = "DELETE FROM {TABLE}example WHERE example_id =%d;";
		database::retrieve($query, 0, (int)$example_id);
		return true;
	}
}
?>
