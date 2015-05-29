<?php

namespace SmWeb;

class letter_model implements model {
	public static $template;
	public static function init() {
		core::loadClass ( 'database' );
		self::$template = array (
				'letter_id' => '',
				'letter_html' => '',
				'letter_html_ts' => '0000-00-00 00:00:00',
				'letter_html_valid' => '0' 
		);
	}
	public static function cache_purge_all() {
		/* Purge every cached revision (will be slow to load next) */
		$query = "UPDATE {TABLE}letter SET letter_html ='', letter_html_valid =0 WHERE 1";
		database::retrieve ( $query, 0 );
	}
	public static function cache_get_html($letter_id) {
		$query = "SELECT letter_html FROM {TABLE}letter WHERE letter_id ='%s' AND letter_html_valid =1;";
		if (! $row = database::retrieve ( $query, 1, $letter_id )) {
			return false;
		}
		return $row ['letter_html'];
	}
	public static function cache_save($letter) {
		/* Test for existing row */
		$query = "SELECT letter_id FROM {TABLE}letter WHERE letter_id ='%s';";
		if ($row = database::retrieve ( $query, 1, $letter ['letter_id'] )) {
			/* Existing entry */
			$query = "UPDATE {TABLE}letter SET letter_html ='%s', letter_html_ts =CURRENT_TIMESTAMP, letter_html_valid =1 WHERE letter_id ='%s';";
			return database::retrieve ( $query, 0, $letter ['letter_html'], $letter ['letter_id'] );
		} else {
			/* New entry needed */
			$query = "INSERT INTO {TABLE}letter (letter_id, letter_html, letter_html_ts, letter_html_valid) VALUES ('%s', '%s', CURRENT_TIMESTAMP, 1);";
			return database::retrieve ( $query, 0, $letter ['letter_id'], $letter ['letter_html'] );
		}
	}
}
