<?php

namespace SmWeb;

class Page_View implements View {
	private static $config;
	public static function init() {
		self::$config = Core::getConfig ( 'Core' );
	}
	public static function view_html(array $data) {
		$config = Core::getConfig ( 'core' );
		$permissions = Core::getPermissions ( 'page' );
		$view_template = dirname ( __FILE__ ) . "/template/Page/view.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
	public static function error_html(array $data) {
		$permissions = Core::getPermissions ( 'page' );
		$view_template = dirname ( __FILE__ ) . "/template/Page/error.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
	public static function create_html(array $data) {
		$permissions = Core::getPermissions ( 'page' );
		$view_template = dirname ( __FILE__ ) . "/template/Page/create.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
	public static function edit_html(array $data) {
		$permissions = Core::getPermissions ( 'page' );
		$view_template = dirname ( __FILE__ ) . "/template/Page/edit.inc";
		include (dirname ( __FILE__ ) . "/template/htmlLayout.php");
	}
}
