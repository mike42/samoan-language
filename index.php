<?php
require_once("api/core.php");

$config = core::getConfig('core');

/* Get page (or go to default if none is specified) */
if(isset($_GET['p']) && $_GET['p'] != '') {
	$arg = explode('/', $_REQUEST['p']);
} else {
	$arg = $config['default']['arg'];
}

/* Get any extension appearing at the end of the request: */
$tail = count($arg) - 1;
$fmtsplit = explode('.', $arg[$tail]);
if(count($fmtsplit) >= 2) {
	/* One or more extensions on word, eg .rss, .tar.gz */
	$arg[$tail] = array_shift($fmtsplit);
	$fmt = implode('.', $fmtsplit);
} else {
	/* No extensions at all */
	$fmt = $config['default']['format'];
}

/* Switch for number of arguments */
if(count($arg) > 2) {
	/* $controller/$action/{foo/bar/baz}.quux */
	$controller = array_shift($arg);
	$action = array_shift($arg);
} elseif(count($arg) == 2) {
	/* No action specified - $controller/(default action)/{foo}.quux */
	$controller = array_shift($arg);
	$action = $config['default']['action'];
} elseif(count($arg) == 1) {
	/* No action or controller */
	$controller = $config['default']['controller'];
	$action = $config['default']['action'];
}

/* Figure out class and method name */
try {
	/* Execute controller code */
	$controllerClassName = $controller.'_controller';
	$controllerMethodName = $action;
	$viewClassName = $controller.'_view';
	$viewMethodName = $action . "_" . $fmt;

	core::loadClass($controllerClassName);
	core::loadClass($viewClassName);
	if(!is_callable($controllerClassName . "::" . $controllerMethodName)) {
		core::fizzle("Controller '$controllerClassName' does not have method '$controllerMethodName'");
	}
	$ret = call_user_func_array(array($controllerClassName, $controllerMethodName), $arg);

	if(isset($ret['view'])) {
		$viewMethodName = $ret['view'] . "_" . $fmt;
	} elseif(isset($ret['error'])) {
		$viewMethodName = 'error' . "_" . $fmt;
	} elseif(isset($ret['redirect'])) {
		core::redirect($ret['redirect']);
	}
	/* Run view code */
	$ret['url'] = core::constructUrl($controller, $action, $arg, $fmt);
	if(!is_callable($viewClassName . "::" .$viewMethodName)) {
		core::fizzle("View '$viewClassName' does not have method '$viewMethodName'");
	}
	$ret = call_user_func_array(array($viewClassName, $viewMethodName), array($ret));
} catch(Exception $e) {
	core::fizzle("Failed to run controller: " . $e);
}
?>
