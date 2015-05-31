<?php
namespace SmWeb;

class Word_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $word;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Word_Model");
		$this -> database = Database::getInstance();
		$this -> word = new Word_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
