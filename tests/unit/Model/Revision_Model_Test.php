<?php
namespace SmWeb;

class Revision_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $revision;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Revision_Model");
		$this -> database = Database::getInstance();
		$this -> revision = new Revision_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
