<?php

namespace SmWeb;

class User_Controller implements Controller {
	private $database;
	private $user;
	public function __construct(database $database) {
		$this->database = $database;
		$this->user = User_Model::getInstance ( $database );
		$this->session = Session::getInstance ( $database );
	}
	public static function init() {
		Core::loadClass ( 'User_Model' );
		Core::loadClass ( 'Session' );
	}
	public function login($id = '') {
		if (! (isset ( $_REQUEST ['submit'] ) && isset ( $_POST ['user_name'] ) && isset ( $_POST ['user_password'] ))) {
			return array (
					'user' => false 
			);
		}
		
		if ($user = $this -> user -> verifyLogin ( $_POST ['user_name'], $_POST ['user_password'] )) {
			if ($this -> session -> loginUser ( $user )) {
				Core::redirect ( Core::constructURL ( 'page', 'view', array (
						'home' 
				), 'html' ) );
				return array (
						'user' => $user 
				);
			} else {
				return array (
						'user' => false,
						'message' => 'An internal error prevented the login. Please try again.' 
				);
			}
		} else {
			return array (
					'user' => false,
					'message' => 'Incorrect username or password' 
			);
		}
	}
	public function logout() {
		$this -> session -> logoutUser ();
		return array();
	}
}
