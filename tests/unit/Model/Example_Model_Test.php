<?php
namespace SmWeb;

class Example_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $example;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Example_Model");
		$this -> database = Database::getInstance();
		$this -> example = new Example_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
