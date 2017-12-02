<?php
class spellingaudio_model {
	private static $template;

	public static function init() {
		core::loadClass('database');

		self::$template = array(
				'spelling_id' => '0',
				'audio_k_style' => '0',
				'audio_uploaded' => '0000-00-00 00:00:00',
				'audio_speaker' => '0');
	}

	/**
	 * @return number Total number of audio recordings currently stored.
	 */
	public static function countAudio() {
		$query = "SELECT (SELECT count(spelling_id) FROM sm_spellingaudio) + (SELECT count(example_id) FROM sm_exampleaudio);";
		if($row = database::get_row(database::retrieve($query))) {
			return (int)$row[0];
		}
		return 0;
	}

	/**
	 * Get some audio data
	 *
	 * @param string $spelling_t_style
	 * @param int $audio_k_style
	 */
	public static function getRowBySpellingTStyle($spelling_t_style, $audio_k_style = 0) {
		$query = "SELECT sm_spellingaudio.* FROM sm_spellingaudio " .
				"JOIN sm_spelling ON sm_spelling.spelling_id =sm_spellingaudio.spelling_id " .
				"WHERE spelling_t_style=? AND audio_k_style =?";
		if($row = database::get_row(database::retrieve($query, [$spelling_t_style, (int)$audio_k_style]))) {
			return database::row_from_template($row, self::$template);
		}
		return false;
	}
}
?>