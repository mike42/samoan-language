<?php
class audio_model {
	private static $template;

	public static function init() {
		core::loadClass('database');

		self::$template = array(
				'audio_id' => '0',
				'audio_spelling_id' => '0',
				'audio_example_id' => '0',
				'audio_uploaded' => '0000-00-00 00:00:00',
				'audio_k_style' => '0',
				'audio_speaker' => '0');
	}

	/**
	 * @return number Total number of words currently stored.
	 */
	public static function countAudio() {
		$query = "SELECT COUNT(audio_id) FROM  {TABLE}audio;";
		if($row = database::retrieve($query, 1)) {
			return (int)$row[0];
		}
		return 0;
	}
}
?>