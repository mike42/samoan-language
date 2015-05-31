<?php
namespace SmWeb;

class ListLang_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $listLang;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("ListLang_Model");
		$this -> database = Database::getInstance();
		$this -> listLang = new ListLang_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
