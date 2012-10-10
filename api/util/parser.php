<?php
/* Uses the bitrevision wikitext parser */
require_once(dirname(__FILE__) . "/../../vendor/wikitext/WikitextParser.php");


class Parser {
	public function init() {
		/* Initialise wikitext parser here */
		WikitextParser::init();
	}

}



?>
