<?php 
namespace SmWeb;

class user_view {
	private static $config;

	public static function init() {
		self::$config = core::getConfig('core');
	}

	public static function login_html($data) {
		$data['title'] = "Log in";
		$view_template = dirname(__FILE__)."/template/user/login.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}

	public static function logout_html($data) {
		$data['title'] = "Logged out";
		$view_template = dirname(__FILE__)."/template/user/logout.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}





}

?>