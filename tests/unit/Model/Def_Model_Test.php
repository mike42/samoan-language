<?php
namespace SmWeb;

class Def_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $def;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Def_Model");
		$this -> database = Database::getInstance();
		$this -> def = new Def_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
