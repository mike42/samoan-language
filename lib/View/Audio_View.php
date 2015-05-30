<?php

namespace SmWeb;

class Audio_View implements View {
	static $config;
	static $config_audio;
	public static function init() {
		self::$config_audio = Core::getConfig ( 'Audio' );
		self::$config = Core::getConfig ( 'Core' );
	}
	public static function error_ogg(array $data) {
		header ( "HTTP/1.1 404 Not Found" );
		echo "<h1>404 Not Found</h1><hr /><p>That audio file does not exist!</p>";
	}
	public static function error_mp3(array $data) {
		self::error_ogg ( $data );
	}
	public static function listen_ogg(array $data) {
		self::redirTo ( $data, 'ogg' );
	}
	public static function listen_mp3(array $data) {
		self::redirTo ( $data, 'mp3' );
	}
	public static function redirTo($data, $format) {
		$url = self::$config_audio ['extern'] . $data ['type'] . '/' . $format . '/' . $data ['fn'] . '.' . $format;
		Core::redirect ( $url );
	}
	public static function view_html(array $data) {
		if (isset ( $data ['spelling'] )) {
			$data ['title'] = "Audio for word";
			self::useTemplate ( 'view.spelling', $data );
		} else {
			$data ['title'] = "Audio for example";
			self::useTemplate ( 'view.example', $data );
		}
	}
	public static function error_html(array $data) {
		if ($data ['error'] == "404") {
			header ( "HTTP/1.0 404 Not Found" );
			$data ['title'] = "Not found";
		}
		self::useTemplate ( "error", $data );
	}
	private static function useTemplate($template, $data) {
		$permissions = Core::getPermissions ( 'audio' );
		$view_template = dirname ( __FILE__ ) . "/template/Audio/$template.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
}
