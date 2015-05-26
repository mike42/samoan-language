<?php
namespace SmWeb;

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
		$query = "SELECT (SELECT count(spelling_id) FROM {TABLE}spellingaudio) + (SELECT count(example_id) FROM {TABLE}exampleaudio);";
		if($row = database::retrieve($query, 1)) {
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
		$query = "SELECT {TABLE}spellingaudio.* FROM sm_spellingaudio " .
				"JOIN sm_spelling ON {TABLE}spelling.spelling_id ={TABLE}spellingaudio.spelling_id " .
				"WHERE spelling_t_style='%s' AND audio_k_style =%d";
		if($row = database::retrieve($query, 1, $spelling_t_style, (int)$audio_k_style)) {
			return database::row_from_template($row, self::$template);
		}
		return false;
	}
}
?>