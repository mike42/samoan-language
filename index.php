<?php

namespace SmWeb;

require_once ("lib/Core.php");

try {
	/* Get page (or go to default if none is specified) */
	if (isset ( $_REQUEST ['p'] ) && $_REQUEST ['p'] != '') {
		$arg = explode ( '/', $_REQUEST ['p'] );
	} else {
		$config = Core::getConfig ( 'Core' );
		$arg = $config ['default'] ['arg'];
	}
	
	Core::loadClass ( 'Request' );
	Core::loadClass ( 'Database' );
	$request = new Request ( $arg, Database::getInstance () );
	$request->execute ();
} catch ( WebException $e ) {
	Core::fatalError ( $e );
} catch ( \Exception $e ) {
	Core::fatalError ( new InternalServerErrorException ( "Unexpected error while processing request: " . $e->getMessage () ) );
}
