<?php

namespace SmWeb;

/* Uses the bitrevision wikitext parser */
require_once (dirname ( __FILE__ ) . "/../../vendor/wikitext/wikitext.php");

/**
 * The custom behaviour of templates/links are defined here:
 */
class SmParserBackend extends \DefaultParserBackend {
	private $word;
	private $page;
	
	public function __construct(Database $database) {
		$this -> word = Word_Model::getInstance($database);
		$this -> page = Page_Model::getInstance($database);
	}
	
	public function getInternalLinkInfo($info) {
		switch ($info ['namespace']) {
			case 'word':
				/* Link to a word and display it with subscript number */
				$info ['url'] = Core::constructURL ( 'word', 'view', array (
						$info ['target'] 
				), 'html' );
				if (trim ( $info ['target'] ) == '') {
					/* Allow linking to [[word:]] for the vocab */
					break;
				}
				
				$newinfo = $info;
				$wlpart = $this -> word -> getSpellingAndNumberFromStr ( $info ['target'] );
				
				if ($info ['title'] == $info ['caption']) {
					/* Change caption if user hasn't set their own */
					$newinfo ['caption'] = $wlpart ['spelling'];
					if ($wlpart ['number'] != 0) {
						$newinfo ['caption'] .= "<sub><small>" . ( int ) $wlpart ['number'] . "</small></sub>";
					}
				}
				
				/* Figure out ID string and look it up */
				$idstr = $this -> word -> getIdStrBySpellingNum ( $wlpart ['spelling'], $wlpart ['number'] );
				if ($this -> word -> getWordIDfromStr ( $idstr ) === false) {
					$newinfo ['exists'] = false;
					$newinfo ['title'] = $idstr . " (definition not known)";
				} else {
					$newinfo ['title'] = $idstr;
				}
				return $newinfo;
			case 'ex' :
				$info ['url'] = Core::constructURL ( 'example', 'view', array (
						$info ['target'] 
				), 'html' );
				break;
			default :
				if ($info ['namespace'] == "") {
					/* Only look at non-namespaced links to avoid breaking interwiki links */
					if (! $page = $this -> page -> getByShort ( $info ['target'] )) {
						/* Red-link pages which don't exist */
						$info ['exists'] = false;
					}
					$info ['url'] = Core::constructURL ( 'page', 'view', array (
							$info ['title'] 
					), 'html' );
				}
		}
		return $info;
	}
	public function getTemplateMarkup($template) {
		$part = explode ( ":", $template );
		if (count ( $part ) == 2) {
			$part [1] = trim ( $part [1] );
			switch (trim ( $part [0] )) {
				case 'word' :
					if ($word = $this -> word -> getByStr ( $part [1] )) {
						return Word_View::linkToWord ( $word, true, false, true );
					}
					break;
				case 'ex' :
					if ($example = Example_Model::getById ( $part [1] )) {
						return Example_View::toHTML ( $example, false );
					}
					break;
			}
		}
		/* If it's not one of the above, try loading it */
		if ($page = $this -> page -> getByShort ( $template )) {
			return $page ['page_rel_revision'] ['revision_text'];
		}
		return "[[$template]]";
	}
	public function getImageInfo($info) {
		$info ['url'] = Parser::$conf ['imgextern'] . 'full/' . $info ['url'];
		$info ['thumb'] = Parser::$conf ['imgextern'] . 'thumb/' . $info ['thumb'];
		return $info;
	}
}

/**
 * Simple wrapper for init()
 */
class Parser {
	private static $instance;
	
	public static $conf; /* Config */
	public static function init() {
		self::$conf = Core::getConfig ( 'parser' );
		
		/* Initialise wikitext parser here */
		Core::loadClass ( "Page_Model" );
		Core::loadClass ( "Word_Model" );
		Core::loadClass ( "Word_View" );
		Core::loadClass ( "Example_Model" );
		Core::loadClass ( "Example_View" );
	}
	
	public static function getInstance(Database $database) {
		if (self::$instance == null) {
			self::$instance = new self ( $database );
		}
		return self::$instance;
	}
	
	public function __construct(Database $database) {
		\WikitextParser::init ();
		\WikitextParser::$backend = new SmParserBackend ($database);
	}
	
	public function parse($text) {
		return \WikitextParser::parse ($text);
	}
}
