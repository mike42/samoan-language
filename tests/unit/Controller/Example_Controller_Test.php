<?php
namespace SmWeb;

class Example_Controller_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $example;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Example_Controller");
		$this -> database = Database::getInstance();
		$this -> example = new Example_Controller($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
