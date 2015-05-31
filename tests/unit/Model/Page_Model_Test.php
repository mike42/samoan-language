<?php
namespace SmWeb;

class Page_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $page;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Page_Model");
		$this -> database = Database::getInstance();
		$this -> page = new Page_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
