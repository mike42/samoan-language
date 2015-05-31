<?php
namespace SmWeb;

class User_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $user;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("User_Model");
		$this -> database = Database::getInstance();
		$this -> user = new User_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
