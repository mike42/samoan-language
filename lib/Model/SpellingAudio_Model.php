<?php

namespace SmWeb;

class SpellingAudio_Model implements Model {
	private static $instance;
	public static $template;
	public $database;
	public function __construct(Database $database) {
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
				'spelling_id' => '0',
				'audio_k_style' => '0',
				'audio_uploaded' => '0000-00-00 00:00:00',
				'audio_speaker' => '0' 
		);
	}
	
	/**
	 *
	 * @return number Total number of audio recordings currently stored.
	 */
	public function countAudio() {
		$query = "SELECT (SELECT count(spelling_id) FROM {TABLE}spellingaudio) + (SELECT count(example_id) FROM {TABLE}exampleaudio);";
		if ($row = $this->database->retrieve ( $query, 1 )) {
			return ( int ) $row [0];
		}
		return 0;
	}
	
	/**
	 * Get some audio data
	 *
	 * @param string $spelling_t_style        	
	 * @param int $audio_k_style        	
	 */
	public function getRowBySpellingTStyle($spelling_t_style, $audio_k_style = 0) {
		$query = "SELECT {TABLE}spellingaudio.* FROM sm_spellingaudio " . "JOIN sm_spelling ON {TABLE}spelling.spelling_id ={TABLE}spellingaudio.spelling_id " . "WHERE spelling_t_style='%s' AND audio_k_style =%d";
		if ($row = $this->database->retrieve ( $query, 1, $spelling_t_style, ( int ) $audio_k_style )) {
			return $this->database->row_from_template ( $row, self::$template );
		}
		return false;
	}
}
