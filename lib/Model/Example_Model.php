<?php

namespace SmWeb;

class Example_Model implements Model {
	private static $instance;
	public static $template;
	public $database;
	public function __construct(database $database) {
		$this->database = $database;
	}
	public static function getInstance(database $database) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
		}
		return self::$instance;
	}
	public static function init() {
		Core::loadClass ( 'Database' );
		
		self::$template = array (
				'example_id' => '0',
				'example_str' => '',
				'example_t_style' => '',
				'example_k_style' => '',
				'example_t_style_recorded' => '0',
				'example_k_style_recorded' => '',
				'example_en' => '',
				'example_en_lit' => '',
				'example_uploaded' => '',
				'example_audio_tag' => '' 
		);
	}
	
	/**
	 * Get a single example using example_id
	 *
	 * @param number $id
	 *        	ID to fetch
	 */
	public function getById($example_id) {
		$sql = "SELECT * FROM {TABLE}example WHERE example_id ='%s';";
		if ($row = $this->database->retrieve ( $sql, 1, ( int ) $example_id )) {
			return $this->database->row_from_template ( $row, self::$template );
		}
		return false;
	}
	
	/**
	 * Get all examples associated with a given definition
	 */
	public function listByDef($def_id) {
		$query = "SELECT * FROM {TABLE}examplerel " . "JOIN {TABLE}example ON example_rel_example_id = example_id " . "WHERE example_rel_def_id =%d";
		$ret = array ();
		if ($res = $this->database->retrieve ( $query, 0, ( int ) $def_id )) {
			while ( $row = $this->database->get_row ( $res ) ) {
				/* Load examples */
				$example = $this->database->row_from_template ( $row, self::$template );
				$ret [] = $example;
			}
		}
		return $ret;
	}
	
	/**
	 * Find examples which mention a given word.
	 * Use to prompt suggested additions to examples
	 */
	public function listByWordMention($spelling_t_style, $word_num) {
		$id = Word_Model::getIdStrBySpellingNum ( $spelling_t_style, $word_num );
		$query = "SELECT * FROM {TABLE}example WHERE example_str like '%%[%s|%%' or example_str like '%%[%s]%%';";
		$ret = array ();
		if ($res = $this->database->retrieve ( $query, 0, $id, $id )) {
			while ( $row = $this->database->get_row ( $res ) ) {
				/* Load examples */
				$example = $this->database->row_from_template ( $row, self::$template );
				$ret [] = $example;
			}
		}
		return $ret;
	}
	
	/**
	 * Create a new example and return the ID
	 */
	public function insert($example_sm, $example_en) {
		$str = self::autobracket ( $example_sm );
		$query = "INSERT INTO {TABLE}example (example_id, example_str, example_t_style, example_k_style, example_t_style_recorded, example_k_style_recorded, example_en, example_en_lit, example_uploaded, example_audio_tag) VALUES (NULL ,  '%s',  '%s',  '%s',  '%d',  '%d', '%s', '%s', CURRENT_TIMESTAMP, '%s');";
		$id = $this->database->retrieve ( $query, 2, $str, $example_sm, '', '0', '0', $example_en, '', '' );
		return $id;
	}
	
	/* Wrap each word in single-brackets */
	private static function autobracket($str) {
		$a = explode ( " ", $str );
		$i = 0;
		foreach ( $a as $b ) {
			$a [$i] = "[" . $a [$i] . "]";
			$i ++;
		}
		return join ( " ", $a );
	}
	
	/**
	 *
	 * @return number Total number of examples currently stored.
	 */
	public function countExamples() {
		$query = "SELECT COUNT(example_id) FROM {TABLE}example;";
		if ($row = $this->database->retrieve ( $query, 1 )) {
			return ( int ) $row [0];
		}
		return 0;
	}
	public function update($example) {
		$query = "UPDATE {TABLE}example SET example_str ='%s', example_en='%s' WHERE example_id =%d";
		$this->database->retrieve ( $query, 0, $example ['example_str'], $example ['example_en'], ( int ) $example ['example_id'] );
	}
	public function delete($example_id) {
		/* Delete an example, after removing it from everywhere it appears */
		$query = "DELETE FROM {TABLE}exampleaudio WHERE example_id =%d;";
		$this->database->retrieve ( $query, 0, ( int ) $example_id ); // NB: this may leave orphan audio files.
		
		$query = "DELETE FROM {TABLE}examplerel WHERE example_rel_example_id =%d;";
		$this->database->retrieve ( $query, 0, ( int ) $example_id );
		
		$query = "DELETE FROM {TABLE}example WHERE example_id =%d;";
		$this->database->retrieve ( $query, 0, ( int ) $example_id );
		return true;
	}
}
