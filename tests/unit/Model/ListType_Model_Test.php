<?php
namespace SmWeb;

class ListType_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $listType;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("ListType_Model");
		$this -> database = Database::getInstance();
		$this -> listType = new ListType_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
