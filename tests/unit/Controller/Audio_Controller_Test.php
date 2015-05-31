<?php
namespace SmWeb;

class Audio_Controller_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $audio;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("Audio_Controller");
		$this -> database = Database::getInstance();
		$this -> audio = new Audio_Controller($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}


}
