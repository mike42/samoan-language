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
		$query = "SELECT * FROM sm_word " .
				"JOIN sm_spelling ON word_spelling = spelling_id " .
				"LEFT JOIN sm_listlang ON word_origin_lang = lang_id " .
				"WHERE word_id =?";
		if($row = database::get_row(database::retrieve($query, [(int)$id]))) {
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
		$query = "SELECT word_id FROM sm_word " .
				"JOIN sm_spelling ON word_spelling = spelling_id " .
				"WHERE spelling_t_style=? and word_num=?";
		if($row = database::get_row(database::retrieve($query, [$spelling, (int)$word_num]))) {
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
	public static function getWordBySpellingAndWordNum($spelling, $word_num) {
		$query = "SELECT * FROM sm_word " .
				"JOIN sm_spelling ON word_spelling = spelling_id " .
				"LEFT JOIN sm_listlang ON word_origin_lang = lang_id " .
				"WHERE spelling_t_style=? and word_num=?";
		if($row = database::get_row(database::retrieve($query, [$spelling, (int)$word_num]))) {
			return self::fromRow($row);
		}
		return false;
	}

	public static function listByLetter($letter) {
		if(strlen($letter) != 1) { /* Single letter strings only */
			return false;
		}
		$query = "SELECT * FROM sm_word " .
				"JOIN sm_spelling ON word_spelling = spelling_id " .
				"LEFT JOIN sm_listlang ON word_origin_lang = lang_id " .
				"WHERE spelling_simple LIKE ? ORDER BY spelling_sortkey, word_num;";
		if($res = database::retrieve($query, [$letter . '%'])) {
			$ret = array();
			while($row = database::get_row($res)) {
				$ret[] = self::fromRow($row);
			}
			return $ret;
		}
		return false;
	}

	public static function listByTypeShort($type_short) {
		$query = "select * from (select distinct def_word_id from sm_def " .
				"join sm_listtype on def_type = type_id where type_short =?) sm_def " .
				"join sm_word on def_word_id = word_id " .
				"join sm_spelling on word_spelling = spelling_id ORDER BY spelling_sortkey, word_num;";
		if($res = database::retrieve($query, [$type_short])) {
			$ret = array();
			while($row = database::get_row($res)) {
				$ret[] = self::fromRow($row);
			}
			return $ret;
		}
		return false;
	}

	private static function getRelativesByID($id) {
		$query = "SELECT * FROM sm_wordrel ".
				"JOIN sm_listreltype ON wordrel_type = rel_type_id " .
				"JOIN sm_word ON wordrel_target = word_id " .
				"JOIN sm_spelling ON word_spelling = spelling_id ".
				"WHERE wordrel_word_id =? " .
				"ORDER BY wordrel_id";
		if(!$res = database::retrieve($query, [(int)$id])) {
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

	public static function getBySpellingSearchKey($spelling_searchkey, $prefix_only = false) {
		$query = "SELECT * FROM sm_word " .
				"JOIN sm_spelling ON word_spelling = spelling_id " .
				"LEFT JOIN sm_listlang ON word_origin_lang = lang_id " .
				"WHERE spelling_searchkey ".($prefix_only ? " LIKE '?%%'" : " =?")." ORDER BY spelling_sortkey, word_num LIMIT 0,50";
		if($res = database::retrieve($query, [$spelling_searchkey])) {
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

	/**
	 * Change a word number (used when shuffling to fill in gaps left by deleted words, for example).
	 * You MUST check that the word exists before running this.
	 *
	 * @param int $word_id
	 * @param int $word_num
	 * @return boolean True always
	 */
	public static function renumber($word_id, $word_num) {
		$query = "UPDATE sm_word SET word_num =? WHERE word_id =?;";
		database::retrieve($query, [(int)$word_num, (int)$word_id]);
		return true;
	}

	/**
	 * Insert a new word with the given spelling and number
	 *
	 * @param int $spelling_id
	 * @param int $word_num
	 */
	public static function add($spelling_id, $word_num) {
		$word = self::$template;
		$word['word_spelling'] = $spelling_id;
		$word['word_num'] = $word_num;
		$query = "INSERT INTO sm_word (word_id, word_spelling, word_num) VALUES (NULL, ?, ?);";
		$word['word_id'] = database::insert($query, [(int)$spelling_id, (int)$word_num]);
		return word_model::getByID($word['word_id']);
	}

	/**
	 * Delete a word and remove all references to it
	 *
	 * @param int $word_id
	 */
	public static function delete($word_id) {
		/* Remove wordrel references */
		$query = "DELETE FROM sm_wordrel WHERE wordrel_word_id =? OR wordrel_target =?;";
		database::retrieve($query, [(int)$word_id, (int)$word_id]);

		/* Blank any redirects that pint here */
		$query = "UPDATE sm_word SET word_redirect_to =0 WHERE word_redirect_to =?;";
		database::retrieve($query, [(int)$word_id]);

		/* Delete example links in definitions*/
		$query = "DELETE sm_examplerel FROM sm_examplerel INNER JOIN sm_def ON example_rel_def_id = def_id WHERE def_word_id =?;";
		database::retrieve($query, [(int)$word_id]);

		/* Delete definitions themselves */
		$query = "DELETE FROM sm_def WHERE def_word_id =?";
		database::retrieve($query, [(int)$word_id]);

		/* Remove the word */
		$query = "DELETE FROM sm_word WHERE word_id =?;";
		database::retrieve($query, [(int)$word_id]);
		return true;
	}

	/**
	 * Set word origin for a given word
	 */
	public static function setOrigin($word) {
		if($word['word_origin_lang'] == '') {
			/* Clear origin */
			$query = "UPDATE sm_word SET word_origin_lang =NULL, word_origin_word ='' WHERE word_id =?;";
			return database::retrieve($query, [(int)$word['word_id']]);
		} else {
			/* Set origin */
			$query = "UPDATE sm_word SET word_origin_lang =?, word_origin_word =? WHERE word_id =?;";
			return database::retrieve($query, [$word['word_origin_lang'], $word['word_origin_word'], (int)$word['word_id']]);
		}
	}

	/**
	 * Set redirect destination for a given word
	 */
	public static function setRedirect($word) {
		if((int)$word['word_redirect_to'] == 0) {
			/* Clear redirect */
			$query = "UPDATE sm_word SET word_redirect_to =NULL WHERE word_id =?;";
			return database::retrieve($query, [(int)$word['word_id']]);
		} else {
			/* Set redirect */
			$query = "UPDATE sm_word SET word_redirect_to =? WHERE word_id =?;";
			return database::retrieve($query, [(int)$word['word_redirect_to'], (int)$word['word_id']]);
		}
	}

	/**
	 * @param int $word_id
	 * @param int $spelling_id
	 * @param int $word_num
	 */
	public static function move($word_id, $spelling_id, $word_num) {
		$query = "UPDATE sm_word SET word_spelling =?, word_num =? WHERE word_id =?;";
		return database::retrieve($query, [(int)$spelling_id, (int)$word_num, (int)$word_id]);
	}

	/**
	 * Return a list of word-relation types for use in a form etc.
	 */
	public static function listRelType() {
		$query = "SELECT * FROM sm_listreltype;";
		if(!$res = database::retrieve($query)) {
			return false;
		}

		$ret = array();
		while($row = database::get_row($res)) {
			$ret[] = database::row_from_template($row, self::$rel_template);
		}
		return $ret;
	}

	/**
	 * Return true if a given rel_type_id exists, false otherwise
	 *
	 * @param string $type_id
	 * @return boolean
	 */
	public function relTypeExists($rel_type_id) {
		$query = "SELECT * FROM sm_listreltype where rel_type_id =?;";
		if(!$row = database::get_row(database::retrieve($query, [$rel_type_id]))) {
			return false;
		}
		return true;
	}

	/**
	 * Relate two words
	 */
	public function relateWords($wordrel_word_id, $wordrel_type, $wordrel_target) {
		$query = "INSERT INTO sm_wordrel (wordrel_id, wordrel_word_id, wordrel_type, " .
				"wordrel_target) VALUES (NULL, ?, ?, ?);";
		return database::insert($query, [(int)$wordrel_word_id, $wordrel_type, (int)$wordrel_target]);
	}

	/**
	 * Un-relate two words
	 */
	public function unRelateWords($wordrel_word_id, $wordrel_type, $wordrel_target) {
		$query = "DELETE FROM sm_wordrel WHERE wordrel_word_id =? AND wordrel_type =? AND wordrel_target =?;";
		return database::retrieve($query, [(int)$wordrel_word_id, $wordrel_type, (int)$wordrel_target]);
	}

	/**
	 * Return true if two words are already related a certain way
	 * @return boolean
	 */
	public function isRelated($wordrel_word_id, $wordrel_type, $wordrel_target) {
		$query = "SELECT * FROM sm_wordrel WHERE wordrel_word_id =? AND wordrel_type =? AND wordrel_target =?;";
		if(!$row = database::get_row(database::retrieve($query, [(int)$wordrel_word_id, $wordrel_type, (int)$wordrel_target]))) {
			return false;
		}
		return true;
	}

	/**
	 * @return number Total number of words currently stored.
	 */
	public static function countWords() {
		$query = "SELECT COUNT(word_id) FROM  sm_word;";
		if($row = database::get_row(database::retrieve($query))) {
			return (int)$row[0];
		}
		return 0;
	}
}
?>