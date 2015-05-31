<?php
namespace SmWeb;

class Word_Controller_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $word;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Word_Controller");
		$this -> database = Database::getInstance();
		$this -> word = new Word_Controller($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
