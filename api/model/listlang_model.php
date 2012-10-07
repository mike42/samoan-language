<?php
class listlang_model {
	public static $template;

	public static function init() {
		core::loadClass('database');
		self::$template = array(
				'lang_id'	=> '',
				'lang_name'	=> '');
	}
}



?>