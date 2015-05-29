<?php
namespace SmWeb;

require_once("api/core.php");
/* Get page (or go to default if none is specified) */
if(isset($_GET['p']) && $_GET['p'] != '') {
	$arg = explode('/', $_REQUEST['p']);
} else {
	$config = core::getConfig('core');
	$arg = $config['default']['arg'];
}

core::loadClass('request');
$request = new request($arg);
$request -> execute();