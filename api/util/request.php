<?php

namespace SmWeb;

use \Exception;

/**
 * A web request.
 */
class request {
	/**
	 *
	 * @var array Request config
	 */
	private $config;
	
	/**
	 *
	 * @var controller
	 */
	private $controller;
	
	/**
	 *
	 * @var view
	 */
	private $view;
	
	/**
	 *
	 * @var unknown
	 */
	private $arg;
	
	/**
	 *
	 * @var unknown
	 */
	private $action;
	
	/**
	 *
	 * @var unknown
	 */
	private $fmt;
	
	/**
	 *
	 * @var unknown
	 */
	private $url;
	
	/**
	 * Construct request
	 */
	public function __construct(array $arg) {
		$this->config = core::getConfig ( 'core' )['default'];
		
		/* Get any extension appearing at the end of the request: */
		$tail = count ( $arg ) - 1;
		$fmtsplit = explode ( '.', $arg [$tail] );
		if (count ( $fmtsplit ) >= 2) {
			/* One or more extensions on word, eg .rss, .tar.gz */
			$arg [$tail] = array_shift ( $fmtsplit );
			$fmt = implode ( '.', $fmtsplit );
		} else {
			/* No extensions at all */
			$fmt = $this->config ['format'];
		}
		
		/* Switch for number of arguments */
		if (count ( $arg ) > 2) {
			/* $controller/$action/{foo/bar/baz}.quux */
			$controllerShortName = array_shift ( $arg );
			$action = array_shift ( $arg );
		} elseif (count ( $arg ) == 2) {
			/* No action specified - $controller/(default action)/{foo}.quux */
			$controllerShortName = array_shift ( $arg );
			$action = $this->config ['action'];
		} elseif (count ( $arg ) == 1) {
			/* No action or controller */
			$controllerShortName = $this->config ['controller'];
			$action = $this->config ['action'];
		}
		
		$this->arg = $arg;
		$this->controller = $this->getController ( $controllerShortName );
		$this->view = $this->getView ( $controllerShortName );
		$this->action = $action;
		$this->fmt = $fmt;
		$this->url = core::constructUrl ( $controllerShortName, $action, $arg, $fmt );
	}
	
	/**
	 *
	 * @param array $arg
	 *        	Execute request
	 */
	public function execute() {
		/* Figure out class and method name */
		try {
			/* Run controller */
			$data = $this->runController ( $this->controller, $this->arg, $this->action );
			if(isset ( $data ['redirect'] )) {
				core::redirect ( $ret ['redirect'] );
				return;
			}
			$this->runView ( $this->view, $data, $this -> action, $this->fmt );
		} catch ( Exception $e ) {
			core::fizzle ( "Failed to run controller: " . $e );
		}
	}
	protected function runController(controller $controller, array $arg, $action) {
		$controllerMethodName = $action;
		$method = array (
				$controller,
				$controllerMethodName 
		);
		if (! is_callable ( $method )) {
			core::fizzle ( "The controller does not support an '$action' action.", 404 );
		}
		$ret = call_user_func_array ( $method, $arg );
		if (! is_array ( $ret )) {
			core::fizzle ( "The controller did not return a valid data array.", 404 );
		}
		$ret ['url'] = $this->url;
		return $ret;
	}
	protected function runView(view $view, array $data, $action, $fmt) {
		/* Change action if the data suggests so  */
		if (isset ( $data ['view'] )) {
			$action = $data ['view'];
		} elseif (isset ( $data ['error'] )) {
			$action = "error";
		}
		/* Run method for real */
		$viewMethodName = $action . "_" . $fmt;
		$method = array (
				$view,
				$viewMethodName 
		);
		if (! is_callable ( $method )) {
			core::fizzle ( "The view does not support an '$action' action.", 404 );
		}
		$ret = call_user_func_array ( $method, array (
				$data 
		) );
		return $ret;
	}
	
	/**
	 *
	 * @param string $name        	
	 * @return controller Any class acting as a controller
	 */
	protected function getController($name) {
		$controllerClassName = $name . '_controller';
		core::loadClass ( $controllerClassName );
		$fullControllerClassName = __NAMESPACE__ . "\\" . $controllerClassName;
		return new $fullControllerClassName ();
	}
	
	/**
	 *
	 * @param string $name        	
	 * @return view Any class acting as a view
	 */
	protected function getView($name) {
		$viewClassName = $name . '_view';
		core::loadClass ( $viewClassName );
		$fullViewClassName = __NAMESPACE__ . "\\" . $viewClassName;
		return new $fullViewClassName ();
	}
}