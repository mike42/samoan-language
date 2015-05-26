<?php
namespace SmWeb;

class page_view {
	private static $config;

	public static function init() {
		self::$config = core::getConfig('core');
	}

	public static function view_html($data) {
		$config = core::getConfig('core');
		$permissions = core::getPermissions('page');
		$view_template = dirname(__FILE__)."/template/page/view.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}


	public static function error_html($data) {
		$permissions = core::getPermissions('page');
		$view_template = dirname(__FILE__)."/template/page/error.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}

	public static function create_html($data) {
		$permissions = core::getPermissions('page');
		$view_template = dirname(__FILE__)."/template/page/create.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}

	public static function edit_html($data) {
		$permissions = core::getPermissions('page');
		$view_template = dirname(__FILE__)."/template/page/edit.inc";
		include(dirname(__FILE__)."/template/htmlLayout.php");
	}
}
?>
