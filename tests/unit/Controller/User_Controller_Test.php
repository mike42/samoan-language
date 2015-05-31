<?php
namespace SmWeb;

class User_Controller_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $user;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("User_Controller");
		$this -> database = Database::getInstance();
		$this -> user = new User_Controller($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
