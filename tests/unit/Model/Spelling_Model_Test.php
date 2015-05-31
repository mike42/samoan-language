<?php
namespace SmWeb;

class Spelling_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $spelling;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Spelling_Model");
		$this -> database = Database::getInstance();
		$this -> spelling = new Spelling_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
