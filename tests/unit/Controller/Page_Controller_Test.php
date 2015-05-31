<?php
namespace SmWeb;

class Page_Controller_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $page;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Page_Controller");
		$this -> database = Database::getInstance();
		$this -> page = new Page_Controller($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
