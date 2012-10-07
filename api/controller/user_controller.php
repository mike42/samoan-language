<?php
class user_controller {
	public function init() {
		core::loadClass('user_model');
	}
	
	public function login($id = '') {
		if(!(isset($_REQUEST['submit']) && isset($_POST['user_name']) && isset($_POST['user_password']))) {
			return array('user' => false);
		}
		
		if($user = user_model::verifyLogin($_POST['user_name'], $_POST['user_password'])) {
			die("verified");
			return array('user' => $user);
		} else {
			return array('user' => false, 'message' => 'Incorrect username or password');
		}
	}
}