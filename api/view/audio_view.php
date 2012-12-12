<?php
class audio_view {
	static $config;
	
	static function init() {
		self::$config = core::getConfig('audio');
	}
	
	static function error_ogg($data) {
		header("HTTP/1.1 404 Not Found");
		echo "<h1>404 Not Found</h1><hr /><p>That audio file does not exist!</p>";
	}
	
	static function error_mp3($data) {
		self::error_ogg($data);
	}
	
	static function listen_ogg($data) {
		self::redirTo($data, 'ogg');
	}
	
	static function listen_mp3($data) {
		self::redirTo($data, 'mp3');
	}
	
	private static function redirTo($data, $format) {
		$url = self::$config['extern'] . $data['type'] . '/' . $format . '/' . $data['fn'] . '.' . $format;
		core::redirect($url);
	}
	
}