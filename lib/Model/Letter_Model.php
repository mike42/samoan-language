<?php

namespace SmWeb;

class Letter_Model implements Model {
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
				'letter_id' => '',
				'letter_html' => '',
				'letter_html_ts' => '0000-00-00 00:00:00',
				'letter_html_valid' => '0' 
		);
	}
	public function cache_purge_all() {
		/* Purge every cached revision (will be slow to load next) */
		$query = "UPDATE {TABLE}letter SET letter_html ='', letter_html_valid =0 WHERE 1";
		$this->database->retrieve ( $query, 0 );
	}
	public function cache_get_html($letter_id) {
		$query = "SELECT letter_html FROM {TABLE}letter WHERE letter_id ='%s' AND letter_html_valid =1;";
		if (! $row = $this->database->retrieve ( $query, 1, $letter_id )) {
			return false;
		}
		return $row ['letter_html'];
	}
	public function cache_save($letter) {
		/* Test for existing row */
		$query = "SELECT letter_id FROM {TABLE}letter WHERE letter_id ='%s';";
		if ($row = $this->database->retrieve ( $query, 1, $letter ['letter_id'] )) {
			/* Existing entry */
			$query = "UPDATE {TABLE}letter SET letter_html ='%s', letter_html_ts =CURRENT_TIMESTAMP, letter_html_valid =1 WHERE letter_id ='%s';";
			return $this->database->retrieve ( $query, 0, $letter ['letter_html'], $letter ['letter_id'] );
		} else {
			/* New entry needed */
			$query = "INSERT INTO {TABLE}letter (letter_id, letter_html, letter_html_ts, letter_html_valid) VALUES ('%s', '%s', CURRENT_TIMESTAMP, 1);";
			return $this->database->retrieve ( $query, 0, $letter ['letter_id'], $letter ['letter_html'] );
		}
	}
}
