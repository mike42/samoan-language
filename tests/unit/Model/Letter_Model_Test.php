<?php
namespace SmWeb;

class Letter_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $letter;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Letter_Model");
		$this -> database = Database::getInstance();
		$this -> letter = new Letter_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
