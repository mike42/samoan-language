<?php

namespace SmWeb;

/* Manage user sessions */
class session {
	private static $started;
	private static $user;
	private static $verified;
	
	/**
	 * Start the session with no user details.
	 */
	public static function init() {
		self::$user = null;
		self::$verified = false;
		
		core::loadClass ( 'user_model' );
		session_start ();
	}
	
	/**
	 * Log in as a given user, storing information in $_SESSION
	 *
	 * @param mixed $user
	 *        	The user to log in as
	 * @return boolean True if the user was logged in successfully, false if the details don't match the database.
	 */
	public static function loginUser($user) {
		/* Use these */
		$_SESSION ['user_id'] = $user ['user_id'];
		$_SESSION ['user_pass'] = $user ['user_pass'];
		return self::verifyUser ();
	}
	
	/**
	 * Log out current user.
	 */
	public static function logoutUser() {
		unset ( $_SESSION ['user_id'] );
		unset ( $_SESSION ['user_pass'] );
		self::$user = null;
		self::$verified = true;
		return true;
	}
	
	/**
	 * Get the role of the currently logged-in user
	 *
	 * @return string role of the current user, or 'anon' if there is no current user
	 */
	public static function getRole() {
		if (! self::$verified) {
			/* Check user info before getting role */
			self::verifyUSer ();
		}
		if (self::$user != null) {
			/* If logged in, use user role */
			if (self::$user ['user_role'] == '') {
				return 'user';
			} else {
				return self::$user ['user_role'];
			}
		}
		
		/* Default to 'anon' for non logged-in users */
		return 'anon';
	}
	
	/**
	 * Get information about currently logged in user, or false if not logged in
	 */
	public static function getUser() {
		if (! self::$verified) {
			self::verifyUser ();
		}
		if (self::$user != null) {
			return self::$user;
		}
		return false;
	}
	
	/**
	 * Check that the user stored in the session has the same password hash as the corresponding database user.
	 * Effectively logs out other sessions on password change.
	 *
	 * @return boolean true if the user is logged in, false if they are not
	 */
	private static function verifyUser() {
		if (isset ( $_SESSION ['user_id'] ) && isset ( $_SESSION ['user_pass'] )) {
			if ($user = user_model::getById ( $_SESSION ['user_id'] )) {
				if ($user ['user_pass'] == $_SESSION ['user_pass']) {
					self::$user = $user;
					self::$verified = true;
					return true;
				}
			}
			/* Session set but user has probably changed password since login */
			self::logoutUser ();
		}
		
		/* Not logged in as valid user */
		self::$user = null;
		self::$verified = true;
		return false;
	}
}
