<?php

namespace SmWeb;

class User_View implements View {
	private static $config;
	public static function init() {
		self::$config = Core::getConfig ( 'Core' );
	}
	public static function login_html(array $data) {
		$data ['title'] = "Log in";
		$view_template = dirname ( __FILE__ ) . "/template/User/login.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
	public static function logout_html(array $data) {
		$data ['title'] = "Logged out";
		$view_template = dirname ( __FILE__ ) . "/template/User/logout.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
}
