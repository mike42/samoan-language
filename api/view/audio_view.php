<?php
class audio_view {
	static $config;
	static $config_audio;

	public static function init() {
		self::$config_audio = core::getConfig('audio');
		self::$config = core::getConfig('core');
	}

	public static function error_ogg($data) {
		header("HTTP/1.1 404 Not Found");
		echo "<h1>404 Not Found</h1><hr /><p>That audio file does not exist!</p>";
	}

	public static function error_mp3($data) {
		self::error_ogg($data);
	}

	public static function listen_ogg($data) {
		self::redirTo($data, 'ogg');
	}

	public static function listen_mp3($data) {
		self::redirTo($data, 'mp3');
	}

	public static static function redirTo($data, $format) {
		$url = self::$config_audio['extern'] . $data['type'] . '/' . $format . '/' . $data['fn'] . '.' . $format;
		core::redirect($url);
	}

	public static function view_html($data) {
		if(isset($data['spelling'])) {
			$data['title'] = "Audio for word";
			self :: useTemplate('view.spelling', $data);
		} else {
			$data['title'] = "Audio for example";
			self :: useTemplate('view.example', $data);
		}
	}

	public static function error_html($data) {
		if($data['error'] == "404") {
			header("HTTP/1.0 404 Not Found");
			$data['title'] = "Not found";
		}
		self::useTemplate("error", $data);
	}

	private static function useTemplate($template, $data) {
		$permissions = core::getPermissions('audio');
		$view_template = dirname(__FILE__)."/template/audio/$template.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}
}
