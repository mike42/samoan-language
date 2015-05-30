<?php

namespace SmWeb;

use Exception;

/**
 * Core class -- Handles links and class-loading amongst other things.
 */
class core {
	/**
	 *
	 * @var arrays Samoan alphabet in English letter-order
	 */
	static $alphabet_en = array (
			"a",
			"e",
			"f",
			"g",
			"h",
			"i",
			"k",
			"l",
			"m",
			"n",
			"o",
			"p",
			"r",
			"s",
			"t",
			"u",
			"v" 
	);
	
	/**
	 *
	 * @var arrays Samoan alphabet in native letter-order
	 */
	static $alphabet_sm = array (
			"a",
			"e",
			"i",
			"o",
			"u",
			"f",
			"g",
			"l",
			"m",
			"n",
			"p",
			"s",
			"t",
			"v",
			"h",
			"k",
			"r" 
	);
	
	/**
	 *
	 * @var configuration
	 */
	private static $config = null;
	
	/**
	 * Ensure that a class is loaded.
	 *
	 * @param string $className
	 *        	Name of the class
	 * @throws Exception Where the class can't be found.
	 */
	static function loadClass($className) {
		if (class_exists ( __NAMESPACE__ . "\\" . $className )) {
			return;
		}
		$sp = explode ( "_", $className );
		
		if (count ( $sp ) == 1) {
			/* If there are no underscores, it should be in util */
			$sp [0] = core::alphanumeric ( $sp [0] );
			$fn = dirname ( __FILE__ ) . "/util/" . $sp [0] . ".php";
		} else {
			/* Otherwise look in the folder suggested by the name */
			$sp [0] = core::alphanumeric ( $sp [0] );
			$sp [1] = core::alphanumeric ( $sp [1] );
			$fn = dirname ( __FILE__ ) . "/" . $sp [1] . "/" . $sp [0] . "_" . $sp [1] . ".php";
		}
		
		if (file_exists ( $fn )) {
			/* Include, & call init function if one is defined */
			require_once ($fn);
			if (is_callable ( "\\SmWeb\\" . $className . "::init" )) {
				call_user_func ( "\\SmWeb\\" . $className . "::init" );
			}
		} else {
			throw new Exception ( "The class '$className' could not be found at $fn." );
		}
	}
	
	/**
	 * Stop with an error message (fatal error).
	 */
	static function fatalError(WebException $e) {
		$heade = "500 Internal Server Error"; // Default
		switch ($e->getCode ()) {
			case WebException::FORBIDDEN :
				$header = "403 Forbidden";
				break;
			case WebException::INTERNAL_SERVER_ERROR :
				$header = "500 Internal Server Error";
				break;
			case WebException::NOT_FOUND :
				$header = "404 Not Found";
				break;
		}
		header ( "HTTP/1.1 " . $header );
		echo "<html>\n" . "\t<head>\n" . "\t\t<title>Uh oh!</title>\n" . "\t</head>\n" . "\t<body>\n" . "\t\t<div style='text-align: center;'>\n" . "\t\t<div style='font-size: 200%'>Uh oh!</div>\n" . "\t\t\t<div>" . self::escapeHTML ( $e->getMessage () ) . "</div>\n" . "\t\t</div>\n" . "\t</body>\n" . "</html>\n";
		exit ( 1 ); // In case of CLI.
	}
	
	/**
	 * Produce a URL to a page.
	 *
	 * @param string $controller
	 *        	Name of the controller
	 * @param string $action
	 *        	Name of the action
	 * @param string $arg
	 *        	Arguments for the controller
	 * @param string $fmt
	 *        	Format to request
	 * @return string URL to the page.
	 */
	static function constructURL($controller, $action, $arg, $fmt) {
		$config = core::getConfig ( 'core' );
		$part = array ();
		
		if (count ( $arg ) == 1 && $action == $config ['default'] ['action']) {
			/* We can abbreviate if there is only one argument and we are using the default view */
			if ($controller != $config ['default'] ['controller']) {
				/* The controller isn't default, need to add that */
				array_push ( $part, urlencode ( $arg [0] ) );
				array_unshift ( $part, urlencode ( $controller ) );
			} else {
				/* default controller and action. Check for default args */
				if ($arg [0] != $config ['default'] ['arg'] [0]) {
					array_push ( $part, urlencode ( $arg [0] ) );
				}
			}
		} else {
			/* urlencode all arguments */
			foreach ( $arg as $a ) {
				array_push ( $part, urlencode ( $a ) );
			}
			
			/* Nothing is default: add controller and view */
			array_unshift ( $part, urlencode ( $controller ), urlencode ( $action ) );
		}
		
		/* Only add format suffix if the format is non-default (ie, strip .html) */
		$fmt_suff = (($fmt != $config ['default'] ['format']) ? "." . urlencode ( $fmt ) : "");
		return $config ['webroot'] . implode ( "/", $part ) . $fmt_suff;
	}
	
	/**
	 *
	 * @param string $to
	 *        	URL to redirect to.
	 */
	static function redirect($to) {
		header ( 'location: ' . $to );
		die ();
	}
	
	/**
	 * Clean out characters for filename safety.
	 *
	 * @param string $inp        	
	 * @return mixed
	 */
	static private function alphanumeric($inp) {
		return preg_replace ( "#[^-a-zA-Z0-9]+#", "-", $inp );
	}
	
	/**
	 *
	 * @param string $className        	
	 * @throws WebException
	 * @return array
	 */
	static public function getConfig($className) {
		if (core::$config == null) {
			/* Load config if it is needed */
			$fn = dirname ( __FILE__ ) . '/config.php';
			if (! file_exists ( $fn )) {
				throw new WebException ( "Configuration file does not exist. Please install the application.", "500" );
			}
			include ($fn);
			core::$config = $config;
		}
		
		if (isset ( core::$config [$className] )) {
			return core::$config [$className];
		} else {
			throw new WebException ( "No configuration for $className", "500" );
		}
	}
	public static function escapeHTML($inp) {
		if (! defined ( 'ENT_HTML401' )) {
			return htmlspecialchars ( $inp, null, 'UTF-8' );
		} else {
			return htmlspecialchars ( $inp, ENT_COMPAT | ENT_HTML401, 'UTF-8' );
		}
	}
	public static function getAlphabet() {
		return self::$alphabet_sm;
	}
	public static function getPermissions($area) {
		core::loadClass ( 'session' );
		$permission = core::getConfig ( 'session' );
		return $permission [session::getRole ()] [$area];
	}
}
abstract class WebException extends Exception {
	const NOT_FOUND = 404;
	const INTERNAL_SERVER_ERROR = 500;
	const FORBIDDEN = 403;
	public function __construct($message, $num) {
		parent::__construct ( $message, $num );
	}
}
class NotFoundException extends WebException {
	public function __construct($message) {
		parent::__construct ( $message, WebException::NOT_FOUND );
	}
}
class ForbiddenException extends WebException {
	public function __construct($message) {
		parent::__construct ( $message, WebException::FORBIDDEN );
	}
}
class InternalServerErrorException extends WebException {
	public function __construct($message) {
		parent::__construct ( $message, WebException::INTERNAL_SERVER_ERROR );
	}
}
interface controller {
	// Up next:
	//public function __construct(database $database);
}
interface view {
}
interface model {
}