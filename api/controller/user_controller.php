<?php
class user_controller {
    public static function init() {
        core::loadClass('user_model');
        core::loadClass('session');
    }

    public static function login($id = '') {
        if(!(isset($_REQUEST['submit']) && isset($_POST['user_name']) && isset($_POST['user_password']))) {
            return array('user' => false);
        }

        if($user = user_model::verifyLogin($_POST['user_name'], $_POST['user_password'])) {
            if(session::loginUser($user)) {
                core::redirect(core::constructURL('page', 'view', array('home'), 'html'));
                return array('user' => $user);
            } else {
                return array('user' => false, 'message' => 'An internal error prevented the login. Please try again.');
            }
        } else {
            return array('user' => false, 'message' => 'Incorrect username or password');
        }
    }

    public static function logout() {
        session::logoutUser();
    }
}