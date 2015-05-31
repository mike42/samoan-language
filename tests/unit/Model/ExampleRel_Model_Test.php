<?php
namespace SmWeb;

class ExampleRel_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $exampleRel;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("ExampleRel_Model");
		$this -> database = Database::getInstance();
		$this -> exampleRel = new ExampleRel_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
