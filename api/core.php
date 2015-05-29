<?php
namespace SmWeb;
use Exception;

/**
 * Core class -- Handles links and class-loading amongst other things.
 */
class core {
	static $alphabet_en		= array("a","e","f","g","h","i","k","l","m","n","o","p","r","s","t","u","v");
	static $alphabet_sm		= array("a","e","i","o","u","f","g","l","m","n","p","s","t","v","h","k","r");
	
	private static $config = null;

	function __autoload($className) {
		$sp = explode("_", $className);

		if(count($sp) == 1) {
			/* If there are no underscores, it should be in util */
			$sp[0] = core::alphanumeric($sp[0]);
			$fn = dirname(__FILE__)."/util/".$sp[0].".php";
		} else {
			/* Otherwise look in the folder suggested by the name */
			$sp[0] = core::alphanumeric($sp[0]);
			$sp[1] = core::alphanumeric($sp[1]);
			$fn = dirname(__FILE__)."/".$sp[1]."/".$sp[0]."_".$sp[1].".php";
		}

		if(file_exists($fn)) {
			require_once($fn);
				
			/* Call init function if one is defined */
			if(is_callable("\\SmWeb\\" . $className . "::init")) {
				try {
					call_user_func("\\SmWeb\\" . $className . "::init");
				} catch(Exception $e) {
					/* If init() threw an exception, chuck a hissy fit */
					core::fizzle("The class '$className' did not initialise: " . $e);
				}
			}
		} else {
			throw new Exception("The class '$className' could not be found at $fn.");
		}
	}

	static function loadClass($className) {
		
		if(!class_exists(__NAMESPACE__ . "\\" . $className)) {
			core::__autoload($className);
		}
	}

	/**
	 * Stop with an error message (fatal error).
	 * 
	 * @param string $info
	 * @param string $code HTTP error code- 404 or 500.
	 */
	static function fizzle($info = '', $code = '404') {
		if($code === '404') {
			header("HTTP/1.1 404 Not Found");
		} else if($code == '500') {
			header("HTTP/1.1 500 Internal Server Error");
		}
		echo "<html>\n" . 
			"\t<head>\n" .
			"\t\t<title>Uh oh!</title>\n" .
			"\t</head>\n" .
			"\t<body>\n" .
			"\t\t<div style='text-align: center; font-size: 200%'>Uh oh!</div>\n" .
			"\t\t<div style='text-align: center;'>" . self::escapeHTML($info) . "</div>\n" .
			"\t</body>\n" .
			"</html>\n";
		exit();
	}

	static function constructURL($controller, $action, $arg, $fmt) {
		$config = core::getConfig('core');
		$part = array();

		if(count($arg) == 1 && $action == $config['default']['action']) {
			/* We can abbreviate if there is only one argument and we are using the default view */
			if($controller != $config['default']['controller'] ) {
				/* The controller isn't default, need to add that */
				array_push($part, urlencode($arg[0]));
				array_unshift($part, urlencode($controller));
			} else {
				/* default controller and action. Check for default args */
				if($arg[0] != $config['default']['arg'][0]) {
					array_push($part, urlencode($arg[0]));
				}
			}
		} else {
			/* urlencode all arguments */
			foreach($arg as $a) {
				array_push($part, urlencode($a));
			}
				
			/* Nothing is default: add controller and view */
			array_unshift($part, urlencode($controller), urlencode($action));
		}

		/* Only add format suffix if the format is non-default (ie, strip .html) */
		$fmt_suff = (($fmt != $config['default']['format'])? "." . urlencode($fmt) : "");
		return $config['webroot'] . implode("/", $part) . $fmt_suff;
	}

	static function redirect($to) {
		global $config;
		header('location: ' . $to);
		die();
	}

	static private function alphanumeric($inp) {
		return preg_replace("#[^-a-zA-Z0-9]+#", "-", $inp);
	}

	static public function getConfig($className) {
		if(core::$config == null) {
			/* Load config if it is needed */
			$fn = dirname(__FILE__).'/config.php';
			if(!file_exists($fn)) {
				core::fizzle("Configuration file does not exist. Please install the application.", "500");
			}
			include($fn);
			core::$config = $config;
		}

		if(isset(core::$config[$className])) {
			return core::$config[$className];
		} else {
			core::fizzle("No configuration for $className", "500");
			return false;
		}
	}

	public static function escapeHTML($inp) {
		if(!defined('ENT_HTML401')) {
			return htmlspecialchars($inp, null, 'UTF-8');
		} else {
			return htmlspecialchars($inp, ENT_COMPAT | ENT_HTML401, 'UTF-8');
		}
	}

	public static function getAlphabet() {
		return self::$alphabet_sm;
	}

	public static function getPermissions($area) {
		core::loadClass('session');
		$permission = core::getConfig('session');
		return $permission[session::getRole()][$area];
	}
}

interface controller {}
interface view {}
interface model {}