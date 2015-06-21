<?php

namespace SmWeb;

abstract class Session {
	private static $instance;

	private $user;
	private $verified;

	/**
	 * Start the session with no user details.
	 */
	public static function init() {
		Core::loadClass ( 'User_Model' );
	}

	/**
	 * @param Database $database
	 * @return Session either a WebSession or CliSession (a sort of dummy which does not set headers) depending on API.
	 */
	public static function getInstance(Database $database = null) {
		if (self::$instance == null) {
			if (php_sapi_name () == "cli") {
				self::$instance = new CliSession ( $database );
			} else {
				self::$instance = new WebSession ( $database );
			}
		}
		return self::$instance;
	}

	/**
	 * @param Database $database
	 */
	public function __construct(Database $database) {
		$this->userModel = User_Model::getInstance ( $database );
		$this->user = null;
		$this->verified = false;
	}

	/**
	 * Get the role of the currently logged-in user
	 *
	 * @return string role of the current user, or 'anon' if there is no current user
	 */
	public function getRole() {
		if (! $this->verified) {
			/* Check user info before getting role */
			$this->verifyUser ();
		}
		if ($this->user != null) {
			/* If logged in, use user role */
			if ($this->user ['user_role'] == '') {
				return 'user';
			} else {
				return $this->user ['user_role'];
			}
		}
	
		/* Default to 'anon' for non logged-in users */
		return 'anon';
	}

	/**
	 * Get information about currently logged in user, or false if not logged in
	 */
	public function getUser() {
		if (! $this->verified) {
			$this->verifyUser ();
		}
		if ($this->user != null) {
			return $this->user;
		}
		return false;
	}

	/**
	 * Log in as a given user, storing information in $_SESSION
	 *
	 * @param mixed $user
	 *        	The user to log in as
	 * @return boolean True if the user was logged in successfully, false if the details don't match the database.
	 */
	abstract public function loginUser(array $user);

	/**
	 * Log out current user.
	 */
	abstract public function logoutUser();
	
	/**
	 * Verify 
	 */
	abstract protected function verifyUser();
}

/**
 * A dummy implementation which does not call php session_ fucntions or perform logins.
 * This is used in a CLI context (such as test cases, maintenance scripts) where
 * these features are unavailable.
 */
class CliSession extends Session {
	public function loginUser(array $user) {
		$this -> user = null;
	}
	
	public function logoutUser() {
		// Do nothing
	}
	
	protected function verifyUser() {
		return false;
	}
}

/**
 * Manage user web sessions
 */
class WebSession extends Session {	
	public function loginUser(array $user) {
		/* Use these */
		$_SESSION ['user_id'] = $user ['user_id'];
		$_SESSION ['user_pass'] = $user ['user_pass'];
		return $this->verifyUser ();
	}

	public function logoutUser() {
		unset ( $_SESSION ['user_id'] );
		unset ( $_SESSION ['user_pass'] );
		$this->user = null;
		$this->verified = true;
		return true;
	}
	
	protected function verifyUser() {
		if (isset ( $_SESSION ['user_id'] ) && isset ( $_SESSION ['user_pass'] )) {
			if ($user = $this->userModel->getById ( $_SESSION ['user_id'] )) {
				if ($user ['user_pass'] == $_SESSION ['user_pass']) {
					$this->user = $user;
					$this->verified = true;
					return true;
				}
			}
			/* Session set but user has probably changed password since login */
			$this->logoutUser ();
		}
		
		/* Not logged in as valid user */
		$this->user = null;
		$this->verified = true;
		return false;
	}

	/**
	 * @param Database $database
	 */
	public function __construct(Database $database) {
		parent::__construct($database);
		session_start ();
	}
}
