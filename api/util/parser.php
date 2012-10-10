<?php
/* Uses the bitrevision wikitext parser */
require_once(dirname(__FILE__) . "/../../vendor/wikitext/WikitextParser.php");

/**
 * The custom behaviour of templates/links are defined here:
 */
class SmParserBackend extends DefaultParserBackend {

	public function getInternalLinkInfo($info) {
		$part = explode(":", $info['dest']);
		if(count($part) == 2 && trim($part[0]) == 'word') {
			/* Link to a word and display it with subscript number */
			$newinfo = $info;
			$part[1] = trim($part[1]);
			$newinfo['dest'] = core::constructURL('word', 'view', array($part[1]), 'html');
			$wlpart = word_model::getSpellingAndNumberFromStr($part[1]);

			if($info['dest'] == $info['caption']) {
				/* Change caption if user hasn't set their own */
				$newinfo['caption'] = $wlpart['spelling'];
				if($wlpart['number'] != 0) {
					$newinfo['caption'] .= "<sub><small>".(int)$wlpart['number']."</small></sub>";
				}
				if(word_model::getIdStrBySpellingNum($wlpart['spelling'], $wlpart['number']) === false) {
					$info['exists'] = false;
				}
			}
			return $newinfo;
		} else {
			$info['dest'] = core::constructURL('page', 'view', array($info['dest']), 'html');
		}
		return $info;
	}

	public function getTemplateMarkup($template) {
		$part = explode(":", $template);
		if(count($part) == 2) {
			$part[1] = trim($part[1]);
			switch(trim($part[0])) {
				case 'word':
					if($word = word_model::getByStr($part[1])) {
						return word_view::linkToWord($word, true, false, true);
					}
				case 'ex':
					
			}
		}
		/* If it's not one of the above, try loading it */
		if($page = page_model::getByShort($template)) {
			return $page['page_rel_revision']['revision_text'];
		}
		return "[[$template]]";
	}
}

/**
 * Simple wrapper for init()
 */
class Parser {
	public function init() {
		/* Initialise wikitext parser here */
		core::loadClass("page_model");
		core::loadClass("word_model");
		core::loadClass("word_view");
		core::loadClass("example_model");
		
		WikitextParser::init();
		WikitextParser::$backend = new SmParserBackend;
	}

}


?>
