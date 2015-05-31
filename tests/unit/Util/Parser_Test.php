<?php
namespace SmWeb;

class Parser_Test extends \PHPUnit_Framework_TestCase {
	private $parser;
	private $database;
	
	public function setUp() {
		Core::loadClass("Parser");
		$this -> database = Database::getInstance();
		$this -> parser = Parser::getInstance($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
