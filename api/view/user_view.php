<?php 
class user_view {
	private static $config;
	
	public static function init() {
		self::$config = core::getConfig('core');
	}
	
	public function login_html($data) {
		$data['title'] = "Log in";
		$view_template = dirname(__FILE__)."/template/user/login.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}	
	
	
	
	
	
	
	
}

?>