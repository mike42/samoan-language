<?php

namespace SmWeb;

/* Manage user sessions */
class Session {
	private static $instance;
	
	private $started;
	private $user;
	private $verified;
	
	/**
	 * Start the session with no user details.
	 */
	public static function init() {
		Core::loadClass ( 'User_Model' );
	}
	
	/**
	 * Log in as a given user, storing information in $_SESSION
	 *
	 * @param mixed $user
	 *        	The user to log in as
	 * @return boolean True if the user was logged in successfully, false if the details don't match the database.
	 */
	public function loginUser($user) {
		/* Use these */
		$_SESSION ['user_id'] = $user ['user_id'];
		$_SESSION ['user_pass'] = $user ['user_pass'];
		return $this->verifyUser ();
	}
	
	/**
	 * Log out current user.
	 */
	public function logoutUser() {
		unset ( $_SESSION ['user_id'] );
		unset ( $_SESSION ['user_pass'] );
		$this->user = null;
		$this->verified = true;
		return true;
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
	 * Check that the user stored in the session has the same password hash as the corresponding database user.
	 * Effectively logs out other sessions on password change.
	 *
	 * @return boolean true if the user is logged in, false if they are not
	 */
	private function verifyUser() {
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
	public function __construct(Database $database) {
		$this->userModel = User_Model::getInstance ( $database );
		$this->user = null;
		$this->verified = false;
		
		session_start ();
	}
	public static function getInstance(Database $database = null) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
			// TODO use fake session if on CLI, to prevent "session_start(): Cannot send session cookie - headers already sent"
		}
		return self::$instance;
	}
}
