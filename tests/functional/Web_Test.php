<?php
namespace SmWeb;

class Web_Test extends \PHPUnit_Extensions_Selenium2TestCase {
	public static $browsers = array (
			// array (
			// 'name' => 'Firefox on Linux',
			// 'browser' => '*firefox /usr/bin/firefox',
			// 'host' => 'localhost',
			// 'port' => 4444,
			// 'timeout' => 30000,
			// 'browserName' => 'firefox'
			// ),
			array (
					'name' => 'Chrome on Linux',
					'browser' => '*chrome /usr/bin/chromium',
					'host' => 'localhost',
					'port' => 4444,
					'timeout' => 30000,
					'browserName' => 'chrome' 
			) 
	);
	protected function setUp() {
		// TODO database setup
		
		$this->setBrowserUrl ( 'https://localhost/sm/' );
	}
	public function testMainPage() {
		$this->url ( 'https://localhost/samoan/' );
		$this->assertEquals ( 'Samoan Language Resources', $this->title () );
	}
	
	/**
	 * Conduct a series of searches
	 */
	public function testSearch() {
		$this->url ( 'https://localhost/samoan/' );
		
		// Search for word
		$search = $this->byName ( 's' );
		$this->assertEquals ( $search->value (), 'Search for a word' );
		$search->value ( 'test' );
		$form = $this->byId ( "searchform" )->submit ();
		$this->assertEquals ( 'Search Vocabulary - Samoan Language Resources', $this->title () );
		$this->assertTrue ( strpos ( $this->source (), "Found 1 word matching your search:" ) !== false );
		$this->assertTrue ( strpos ( $this->source (), "test" ) !== false );
		
		// Search for word in k-style
		$search = $this->byName ( 's' );
		$this->assertEquals ( $search->value (), 'Search for a word' );
		$search->value ( 'kest' );
		$form = $this->byId ( "searchform" )->submit ();
		$this->assertEquals ( 'Search Vocabulary - Samoan Language Resources', $this->title () );
		$this->assertTrue ( strpos ( $this->source (), "Found 1 word matching your search:" ) !== false );
		$this->assertTrue ( strpos ( $this->source (), "test" ) !== false );
		
		// Search for word that doesn't exist
		$search = $this->byName ( 's' );
		$this->assertEquals ( $search->value (), 'Search for a word' );
		$search->value ( 'testtest' );
		$form = $this->byId ( "searchform" )->submit ();
		$this->assertEquals ( 'Search Vocabulary - Samoan Language Resources', $this->title () );
		$this->assertTrue ( strpos ( $this->source (), "Found 0 words matching your search:" ) !== false );
		
		// $this->assertEquals ( 'Samoan Language Resources', $this->title () );
		// $dom = new DomDocument;
		// $dom -> loadHTML($this -> source());
		// echo $dom -> validate();
		// $this -> assertTrue($dom -> validate());
		// $this ->
	}
}
?>