<?php
class audio_model {
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
}
?>