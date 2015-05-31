<?php

namespace SmWeb;

use \Exception;

/**
 * A web request.
 */
class Request {
	/**
	 *
	 * @var array Configuration for requests.
	 */
	private $config;
	
	/**
	 *
	 * @var controller Controller to use
	 */
	private $controller;
	
	/**
	 *
	 * @var view View to use
	 */
	private $view;
	
	/**
	 *
	 * @var array Arguments to apply to the controller.
	 */
	private $arg;
	
	/**
	 *
	 * @var string Action to perform, such as 'view' or 'edit'
	 */
	private $action;
	
	/**
	 *
	 * @var string Format being requested. Usually 'html'.
	 */
	private $fmt;
	
	private $session;
	
	/**
	 *
	 * @var string URL being requested, in canonical form.
	 */
	private $url;
	
	public static function init() {
		core::loadClass('Session');
		
	}
	
	/**
	 * Construct request
	 */
	public function __construct(array $arg, Database $database) {
		$this->config = Core::getConfig ( 'core' )['default'];
		
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
		$controllerShortName = ucfirst($controllerShortName); // 'page' -> 'Page'
		
		try {
			$this->controller = $this->getController ( $controllerShortName, $database );
			$this->view = $this->getView ( $controllerShortName );
		} catch ( Exception $e ) {
			throw new NotFoundException ( "That page does not exist. " . $e -> getMessage() );
		}
		$this->arg = $arg;
		$this->action = $action;
		$this->fmt = $fmt;
		$this->url = Core::constructUrl ( $controllerShortName, $action, $arg, $fmt );
		$this -> session = Session::getInstance($database);
	}
	
	/**
	 * Run the controller to gather data, then the view to render it.
	 */
	public function execute() {
		/* Run controller */
		$data = $this->runController ( $this->controller, $this->arg, $this->action );
		if (isset ( $data ['redirect'] )) {
			Core::redirect ( $data ['redirect'] );
			return;
		}
		$data ['url'] = $this->url;
		/* Change action if the data suggests so */
		$action = $this->action;
		if (isset ( $data ['view'] )) {
			$action = $data ['view'];
		} elseif (isset ( $data ['error'] )) {
			$action = "error";
		}
		$this->runView ( $this->view, $data, $action, $this->fmt );
	}
	
	/**
	 * Run the controller with the given arguments & action
	 *
	 * @param Controller $controller
	 *        	Controller to use
	 * @param array $arg
	 *        	Arguments to pass to the controller
	 * @param string $action
	 *        	Action to run
	 * @return array Associatve array of data from the controller.
	 */
	protected function runController(Controller $controller, array $arg, $action) {
		$controllerMethodName = $action;
		$method = array (
				$controller,
				$controllerMethodName 
		);
		if (! is_callable ( $method )) {
			throw new NotFoundException ( "The controller does not support an '$action' action." );
		}
		$ret = call_user_func_array ( $method, $arg );
		if (! is_array ( $ret )) {
			throw new InternalServerErrorException ( "The controller did not return valid data." );
		}
		return $ret;
	}
	/**
	 * Run the view with the given data & action.
	 *
	 * @param View $view
	 *        	View to use
	 * @param array $data
	 *        	Data to pass to the view.
	 * @param string $action
	 *        	Associated action.
	 * @param fmt $fmt
	 *        	Output format to request.
	 * @return mixed
	 */
	protected function runView(View $view, array $data, $action, $fmt) {
		/* Run method for real */
		$viewMethodName = $action . "_" . $fmt;
		$method = array (
				$view,
				$viewMethodName 
		);
		if (! is_callable ( $method )) {
			throw new NotFoundException ( "The view does not support an '$action' action." );
		}
		$ret = call_user_func_array ( $method, array (
				$data 
		) );
		return $ret;
	}
	
	/**
	 * Instantiate and return a controller by name.
	 *
	 * @param string $name
	 *        	Name of the controller
	 * @param Database $database
	 *        	Reference to database object to pass to the controller.
	 * @return Controller Any class acting as a controller
	 */
	protected function getController($name, Database $database) {
		$controllerClassName = $name . '_Controller';
		Core::loadClass ( $controllerClassName );
		$fullControllerClassName = __NAMESPACE__ . "\\" . $controllerClassName;
		return new $fullControllerClassName ( $database );
	}
	
	/**
	 * Instantiate and return a view by name.
	 *
	 * @param string $name
	 *        	Name of the view.
	 * @return View Any class acting as a view
	 */
	protected function getView($name) {
		$viewClassName = $name . '_View';
		Core::loadClass ( $viewClassName );
		$fullViewClassName = __NAMESPACE__ . "\\" . $viewClassName;
		return new $fullViewClassName ();
	}
}