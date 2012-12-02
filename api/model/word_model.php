<?php
class word_model {
	public static $template;
	private static $rel_template; /* Template for how words relate to eachother */

	public static function init() {
		core::loadClass('database');
		core::loadClass('spelling_model');
		core::loadClass('listlang_model');
		core::loadClass('def_model');

		/* Structure for words  */
		self::$template = array(
			'word_id'			=> '',
			'word_spelling'		=> '',
			'word_num'			=> '',
			'word_origin_lang'	=> '',
			'word_origin_word'	=> '',
			'word_auto'			=> '0',
			'word_redirect_to'	=> '0',
			'rel_spelling'		=> spelling_model::$template,
			'rel_lang'			=> listlang_model::$template,
			'rel_def'			=> array(),
			'rel_target'		=> false,
			'rel_words'			=> array());
		
		/* Structure for how words relate (synonyms, antonyms, etc) */
		self::$rel_template = array(
			'word'					=> self::$template,
			'wordrel_id'			=> '',
			'wordrel_word_id'		=> '',
			'wordrel_type'			=> '',
			'wordrel_target'		=> '',
			'rel_type_id'			=> '',
			'rel_type_short'		=> '',
			'rel_type_long'			=> '',
			'rel_type_long_label'	=> '');
	}
	
	/**
	 * 
	 * Get a word by ID
	 * @param int $id		ID of the word to fetch
	 * @param int $depth	Recursive counter to prevent resolving of cyclical redirects
	 * @return The word, or false if no such word exists
	 */
	public static function getByID($id, $depth = 0) {
		$query = "SELECT * FROM {TABLE}word " .
					"JOIN {TABLE}spelling ON word_spelling = spelling_id " .
					"LEFT JOIN {TABLE}listlang ON word_origin_lang = lang_id " .
					"WHERE word_id =%d";
		if($row = database::retrieve($query, 1, (int)$id)) {
			return self::fromRow($row, $depth);
		}
		return false;
	}
	
	/**
	 * @param string $text A word's spelling and number, eg 'foo3' or 'bar'.
	 * @return number|boolean Returns the ID of a word, or false if no such word exists
	 */
	public static function getWordIDfromStr($text) {
		if(is_numeric($text)) {
			return (int)$text;
		}
		
		$part = self::getSpellingAndNumberFromStr($text);
		return self::getWordIDfromSpellingAndWordNum($part['spelling'], $part['number']);
	}
	
	/**
	 * Get a word by the string representing its spelling and number (eg abc1, foo, cat3)
	 * 
	 * @param string $string
	 */
	public static function getByStr($string) {
		$part = self::getSpellingAndNumberFromStr($string);
		return self::getWordBySpellingAndWordNum($part['spelling'], $part['number']);
	}
	
	/**
	 * Split word into string and number parts.
	 * Eg "foo4" becomes array([spelling] => foo [number] => 4)
	 * 
	 * @param string $text
	 * @return multitype:number string The 
	 */
	public static function getSpellingAndNumberFromStr($text) {
		$len = strlen($text);
		$part_string = "";
		$part_number = "";
		for($i = 0; $i < $len; $i++) {
			$c = substr($text,$i,1);
			if(is_numeric($c)) {
				$part_number .= $c;
			} else {
				$part_number = "";
				$part_string .= $c;
			}
		}
		return array('spelling' => $part_string, 'number' => (int)$part_number);
	}
	
	/**
	 * Get ID of a word based on spelling and number. Eg "Apa", 1 might return 25.
	 * 
	 * @param string $spelling
	 * @param number $word_num
	 * @return number|boolean The ID of the word, or false if it does not exist
	 */
	private static function getWordIDfromSpellingAndWordNum($spelling, $word_num) {
		$query = "SELECT word_id FROM {TABLE}word " .
					"JOIN {TABLE}spelling ON word_spelling = spelling_id " .
					"WHERE spelling_t_style='%s' and word_num='%d'";
		if($row = database::retrieve($query, 1, $spelling, (int)$word_num)) {
				return (int)$row['word_id'];
		}
		return false;
	}
	
	/**
	 * Get word based on spelling and number.
	 * Use getWordIDfromSpellingAndWordNum() if you are just checking if a word exists as it avoids the extra processing.
	 *
	 * @param string $spelling
	 * @param number $word_num
	 * @return unknown The word, or false if it does not exist
	 */
	private static function getWordBySpellingAndWordNum($spelling, $word_num) {
		$query = "SELECT * FROM {TABLE}word " .
					"JOIN {TABLE}spelling ON word_spelling = spelling_id " .
					"LEFT JOIN {TABLE}listlang ON word_origin_lang = lang_id " .
					"WHERE spelling_t_style='%s' and word_num='%d'";
		if($row = database::retrieve($query, 1, $spelling, (int)$word_num)) {
			return self::fromRow($row);
		}
		return false;
	}
	
	public static function listByLetter($letter) {
		if(strlen($letter) != 1) { /* Single letter strings only */
			return false;
		}
		$query = "SELECT * FROM {TABLE}word " .
					"JOIN {TABLE}spelling ON word_spelling = spelling_id " .
					"LEFT JOIN {TABLE}listlang ON word_origin_lang = lang_id " .
					"WHERE spelling_simple LIKE '%s%%' ORDER BY spelling_sortkey;";
		if($res = database::retrieve($query, 0, $letter)) {
			$ret = array();
			while($row = database::get_row($res)) {
				$ret[] = self::fromRow($row);
			}
			return $ret;
		}
		return false;
	}
	
	public static function listByTypeShort($type_short) {
		$query = "select * from (select distinct def_word_id from {TABLE}def " .
					"join {TABLE}listtype on def_type = type_id where type_short ='%s') sm_def " .
					"join {TABLE}word on def_word_id = word_id " .
					"join {TABLE}spelling on word_spelling = spelling_id ORDER BY spelling_sortkey;";
		if($res = database::retrieve($query, 0, $type_short)) {
			$ret = array();
			while($row = database::get_row($res)) {
				$ret[] = self::fromRow($row);
			}
			return $ret;
		}
		return false;
	} 
	
	private static function getRelativesByID($id) {
		$query = "SELECT * FROM {TABLE}wordrel ".
					"JOIN {TABLE}listreltype ON wordrel_type = rel_type_id " .
					"JOIN {TABLE}word ON wordrel_target = word_id " .
					"JOIN {TABLE}spelling ON word_spelling = spelling_id ".
					"WHERE wordrel_word_id =%d " .
					"ORDER BY wordrel_id";
		if(!$res = database::retrieve($query, 0, (int)$id)) {
			return false;
		}
		
		$ret = array();
		while($row = database::get_row($res)) {
			/* Target word */
			$word = database::row_from_template($row, self::$template);
			$word['rel_spelling'] = database::row_from_template($row, spelling_model::$template);
			/* Relationship */
			$wordrel = database::row_from_template($row, self::$rel_template);
			$wordrel['word'] = $word;
			if(isset($ret[$wordrel['wordrel_type']])) {
				$ret[$wordrel['wordrel_type']][] = $wordrel;
			} else {
				$ret[$wordrel['wordrel_type']] = array($wordrel);
			}
		}
		return $ret;
	}
	
	private static function fromRow($row, $depth = 0) {
		$word = database::row_from_template($row, self::$template);
		$word['rel_spelling'] =  database::row_from_template($row, spelling_model::$template);
		$word['rel_lang'] =  database::row_from_template($row, listlang_model::$template);
		$word['rel_def'] = def_model::listByWord($word['word_id']);
		$word['rel_words'] = self::getRelativesByID($word['word_id']);
		if($word['word_redirect_to'] != '0' && $depth == 0) {
			$word['rel_target'] = self::getByID($word['word_redirect_to'], $depth + 1);
		}
		return $word;
	}
	
	public static function getBySpellingSearchKey($spelling_searchkey) {
		$query = "SELECT * FROM {TABLE}word " .
				"JOIN {TABLE}spelling ON word_spelling = spelling_id " .
				"LEFT JOIN {TABLE}listlang ON word_origin_lang = lang_id " .
				"WHERE spelling_searchkey = '%s' ORDER BY spelling_sortkey";
		if($res = database::retrieve($query, 0, $spelling_searchkey)) {
			$ret = array();
			while($row = database::get_row($res)) {
				$ret[] = self::fromRow($row);
			}
			return $ret;
		}
		return false;
	}
	
	public static function getIdStrBySpellingNum($spelling_t_style, $word_num) {
		return $spelling_t_style.(($word_num != 0)? (int)$word_num : "");
	}
}
?>