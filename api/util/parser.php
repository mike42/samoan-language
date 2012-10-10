<?php
/* Uses the bitrevision wikitext parser */
require_once(dirname(__FILE__) . "/../../vendor/wikitext/WikitextParser.php");

/**
 * The custom behaviour of templates/links are defined here:
 */
class SmParserBackend extends DefaultParserBackend {

	public function getInternalLinkInfo($info) {
		/* Take people to the right place
		 * TODO lookup page/word to do redlinks here.
		 */
		$part = explode(":", $info['dest']);
		if(count($part) == 2 && trim($part[0]) == 'word') {
			$info['dest'] = core::constructURL('word', 'view', array(trim($part[1])), 'html');
		} else {
			$info['dest'] = core::constructURL('page', 'view', array($info['dest']), 'html');
		}
		return $info;
	}

	public function getTemplateMarkup($template) {
		return "Template: $template";
	}
}

/**
 * Simple wrapper for init()
 */
class Parser {
	public function init() {
		/* Initialise wikitext parser here */
		WikitextParser::init();
		WikitextParser::$backend = new SmParserBackend;
	}

}


?>
