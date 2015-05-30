<?php
namespace SmWeb;

class Database_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	
	public function setUp() {
		Core::loadClass("Database");
		$this -> database = Database::getInstance();
	}
	
	public function testNothing() {
		//	TODO 
	}


}
