<?php

namespace SmWeb;

use Exception;

require_once ("api/core.php");

try {
	/* Get page (or go to default if none is specified) */
	if (isset ( $_REQUEST ['p'] ) && $_REQUEST ['p'] != '') {
		$arg = explode ( '/', $_REQUEST ['p'] );
	} else {
		$config = core::getConfig ( 'core' );
		$arg = $config ['default'] ['arg'];
	}
	
	core::loadClass ( 'request' );
	core::loadClass ( 'database' );
	$request = new request ( $arg, new database () );
	$request->execute ();
} catch ( WebException $e ) {
	core::fatalError ( $e );
} catch ( Exception $e ) {
	core::fatalError ( new InternalServerErrorException ( "Unexpected error while processing request: " . $e->getMessage () ) );
}
