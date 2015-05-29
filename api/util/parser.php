<?php

namespace SmWeb;

/* Uses the bitrevision wikitext parser */
require_once (dirname ( __FILE__ ) . "/../../vendor/wikitext/wikitext.php");

/**
 * The custom behaviour of templates/links are defined here:
 */
class SmParserBackend extends \DefaultParserBackend {
	public function getInternalLinkInfo($info) {
		switch ($info ['namespace']) {
			case 'word':
				/* Link to a word and display it with subscript number */
				$info ['url'] = core::constructURL ( 'word', 'view', array (
						$info ['target'] 
				), 'html' );
				if (trim ( $info ['target'] ) == '') {
					/* Allow linking to [[word:]] for the vocab */
					break;
				}
				
				$newinfo = $info;
				$wlpart = word_model::getSpellingAndNumberFromStr ( $info ['target'] );
				
				if ($info ['title'] == $info ['caption']) {
					/* Change caption if user hasn't set their own */
					$newinfo ['caption'] = $wlpart ['spelling'];
					if ($wlpart ['number'] != 0) {
						$newinfo ['caption'] .= "<sub><small>" . ( int ) $wlpart ['number'] . "</small></sub>";
					}
				}
				
				/* Figure out ID string and look it up */
				$idstr = word_model::getIdStrBySpellingNum ( $wlpart ['spelling'], $wlpart ['number'] );
				if (word_model::getWordIDfromStr ( $idstr ) === false) {
					$newinfo ['exists'] = false;
					$newinfo ['title'] = $idstr . " (definition not known)";
				} else {
					$newinfo ['title'] = $idstr;
				}
				return $newinfo;
			case 'ex' :
				$info ['url'] = core::constructURL ( 'example', 'view', array (
						$info ['target'] 
				), 'html' );
				break;
			default :
				if ($info ['namespace'] == "") {
					/* Only look at non-namespaced links to avoid breaking interwiki links */
					if (! $page = page_model::getByShort ( $info ['target'] )) {
						/* Red-link pages which don't exist */
						$info ['exists'] = false;
					}
					$info ['url'] = core::constructURL ( 'page', 'view', array (
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
					if ($word = word_model::getByStr ( $part [1] )) {
						return word_view::linkToWord ( $word, true, false, true );
					}
					break;
				case 'ex' :
					if ($example = example_model::getById ( $part [1] )) {
						return example_view::toHTML ( $example, false );
					}
					break;
			}
		}
		/* If it's not one of the above, try loading it */
		if ($page = page_model::getByShort ( $template )) {
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
	public static $conf; /* Config */
	public static function init() {
		self::$conf = core::getConfig ( 'parser' );
		
		/* Initialise wikitext parser here */
		core::loadClass ( "page_model" );
		core::loadClass ( "word_model" );
		core::loadClass ( "word_view" );
		core::loadClass ( "example_model" );
		core::loadClass ( "example_view" );
		
		\WikitextParser::init ();
		\WikitextParser::$backend = new SmParserBackend ();
	}
}
