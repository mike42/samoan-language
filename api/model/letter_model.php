<?php

class letter_model {
	public static $template;

	public static function init() {
		core::loadClass('database');
		self::$template = array(
				'letter_id'			=> '',
				'letter_html'		=> '',
				'letter_html_ts'	=> '0000-00-00 00:00:00',
				'letter_html_valid'	=> '0');
	}

	public static function cache_purge_all() {
		/* Purge every cached revision (will be slow to load next) */
		$query = "UPDATE sm_letter SET letter_html ='', letter_html_valid =0 WHERE 1";
		database::retrieve($query);
	}

	public static function cache_get_html($letter_id) {
		$query = "SELECT letter_html FROM sm_letter WHERE letter_id =? AND letter_html_valid =1;";
		if(!$row = database::get_row(database::retrieve($query, [$letter_id]))) {
			return false;
		}
		return $row['letter_html'];
	}

	public static function cache_save($letter) {
		/* Test for existing row */
		$query = "SELECT letter_id FROM sm_letter WHERE letter_id =?;";
		if($row = database::get_row(database::retrieve($query, [$letter['letter_id']]))) {
			/* Existing entry */
			$query = "UPDATE sm_letter SET letter_html =?, letter_html_ts =CURRENT_TIMESTAMP, letter_html_valid =1 WHERE letter_id =?;";
			return database::retrieve($query, [$letter['letter_html'], $letter['letter_id']]);
		} else {
			/* New entry needed */
			$query = "INSERT INTO sm_letter (letter_id, letter_html, letter_html_ts, letter_html_valid) VALUES (?, ?, CURRENT_TIMESTAMP, 1);";
			return database::retrieve($query, [$letter['letter_id'], $letter['letter_html']]);
		}
	}
}

?>
