<?php
namespace SmWeb;

class SpellingAudio_Model_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $spellingAudio;
	
	public function setUp() {
		Core::loadClass("Database");
		Core::loadClass("SpellingAudio_Model");
		$this -> database = Database::getInstance();
		$this -> spellingAudio = new SpellingAudio_Model($this -> database);
	}
	
	public function testNothing() {
		//	TODO 
	}

}
