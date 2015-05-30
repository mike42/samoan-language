<?php

namespace SmWeb;

class User_Controller implements Controller {
	public static function init() {
		Core::loadClass ( 'User_Model' );
		Core::loadClass ( 'Session' );
	}
	public static function login($id = '') {
		if (! (isset ( $_REQUEST ['submit'] ) && isset ( $_POST ['user_name'] ) && isset ( $_POST ['user_password'] ))) {
			return array (
					'user' => false 
			);
		}
		
		if ($user = User_Model::verifyLogin ( $_POST ['user_name'], $_POST ['user_password'] )) {
			if (Session::loginUser ( $user )) {
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
	public static function logout() {
		Session::logoutUser ();
	}
}
