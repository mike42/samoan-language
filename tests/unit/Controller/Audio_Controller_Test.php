<?php

namespace SmWeb;

class Audio_Controller_Test extends \PHPUnit_Framework_TestCase {
	private $database;
	private $audioController;
	private $spelling;
	private $spellingAudio;
	public function setUp() {
		Core::loadClass ( "Database" );
		Core::loadClass ( "Audio_Controller" );
		$this->database = Database::getInstance ();
		// Set up object with mock dependencies
		$this->audioController = new Audio_Controller ( $this->database );
		$this->spelling = $this->getMockBuilder ( 'SmWeb\\Spelling_Model' )->setConstructorArgs ( array (
				$this->database 
		) )->getMock ();
		$this->spellingAudio = $this->getMockBuilder ( 'SmWeb\\SpellingAudio_Model' )->setConstructorArgs ( array (
				$this->database 
		) )->getMock ();
		$this->audioController->setSpelling ( $this->spelling );
		$this->audioController->setSpellingAudio ( $this->spellingAudio );
	}
	public function testViewNotFound() {
		// View audio for spelling that doesn't exist
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( false ) );
		$ret = $this->audioController->view ( 'spelling', 'testViewNotFound' );
		$this->assertArrayHasKey ( "error", $ret );
		$this->assertEquals ( "404", $ret ['error'] );
	}
	public function testViewBadType() {
		// View audio for something other than spelling
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( false ) );
		$ret = $this->audioController->view ( 'foobar', 'testViewBadType' );
		$this->assertArrayHasKey ( "error", $ret );
		$this->assertEquals ( "404", $ret ['error'] );
	}
	public function testViewNoAudio() {
		// View audio for valid spelling which does not have audio
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( Spelling_Model::$template ) );
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->returnValue ( false ) );
		$ret = $this->audioController->view ( 'spelling', 'testViewNoAudio' );
		$this->assertArrayNotHasKey ( "error", $ret );
		$this->assertArrayHasKey ( "spelling", $ret );
		$this->assertArrayNotHasKey ( "spellingaudio", $ret );
	}
	public function testViewWithAudio() {
		// View spelling w/ audio
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( Spelling_Model::$template ) );
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->returnValue ( SpellingAudio_Model::$template ) );
		$ret = $this->audioController->view ( 'spelling', 'testViewWithAudio' );
		$this->assertArrayNotHasKey ( "error", $ret );
		$this->assertArrayHasKey ( "spelling", $ret );
		$this->assertArrayHasKey ( "spellingaudio", $ret );
	}
	public function testListenSpellingNotFound() {
		// Spelling does not exist
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( false ) );
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->returnValue ( false ) );
		// T-style search
		$ret = $this->audioController->listen ( 'spelling', 'testListenSpellingNotFound' );
		$this->assertArrayHasKey ( "error", $ret );
		$this->assertEquals ( "404", $ret ['error'] );
		// K-style search
		$ret = $this->audioController->listen ( 'spelling-k', 'testListenSpellingNotFound' );
		$this->assertArrayHasKey ( "error", $ret );
		$this->assertEquals ( "404", $ret ['error'] );
	}
	public function testListenSpellingAudioNotFound() {
		// Spelling exists, but audio for it does not (only useful in k-style search)
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( Spelling_Model::$template ) );
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->returnValue ( false ) );
		$ret = $this->audioController->listen ( 'spelling-k', 'testListenSpellingAudioNotFound' );
	}
	public function testListenSpelling() {
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->returnValue ( SpellingAudio_Model::$template ) );
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( Spelling_Model::$template ) );
		// T-style search
		$ret = $this->audioController->listen ( 'spelling', 'testListenSpelling' );
		$this->assertArrayHasKey ( "fn", $ret );
		// K-style search
		$ret = $this->audioController->listen ( 'spelling-k', 'testListenSpelling' );
		$this->assertArrayHasKey ( "fn", $ret );
	}
	public function testListenSpellingFallbackOk() {
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->onConsecutiveCalls ( false, SpellingAudio_Model::$template ) );
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( Spelling_Model::$template ) );
		// K-style search falling back on T-style spelling for audio
		$ret = $this->audioController->listen ( 'spelling-k', 'testListenSpellingFallbackOk' );
		$this->assertArrayHasKey ( "fn", $ret );
	}
	public function testListenSpellingFallbackFail() {
		// K-style search failing to fall back on T-style spelling for audio, because they are different
		$spellingVal = Spelling_Model::$template;
		$spellingVal ['spelling_t_style'] = "tele";
		$spellingVal ['spelling_k_style'] = "kele";
		$this->spelling->method ( 'getBySpelling' )->will ( $this->returnValue ( $spellingVal ) );
		$this->spellingAudio->method ( 'getRowBySpellingTStyle' )->will ( $this->onConsecutiveCalls ( false, SpellingAudio_Model::$template ) );
		$ret = $this->audioController->listen ( 'spelling-k', 'testListenSpellingFallbackFail' );
		// $this -> assertArrayHasKey("error", $ret);
		// $this -> assertEquals("404", $ret['error']);
	}
	public function testListenPlaceholders() {
		// K style example audio (not implemented)
		$ret = $this->audioController->listen ( 'example', 'testListenPlaceholders' );
		$this->assertArrayHasKey ( "error", $ret );
		$this->assertEquals ( "404", $ret ['error'] );
		// K style examples (also not implemented)
		$ret = $this->audioController->listen ( 'example-k', 'testListenPlaceholders' );
		$this->assertArrayHasKey ( "error", $ret );
		$this->assertEquals ( "404", $ret ['error'] );
	}
}
