<?php
namespace SmWeb;

if($data['error'] == '403') {
	echo "<p>You do not have permission to view this page. You may have to log in.</p>";
} else {
	if(isset($data['word'])) {
		echo "<p>The vocabulary doesn't contain the exact word you were looking for. Please try using the " .
				"search function to look for similar words, or return to the <a href=\"" . self::$config['webroot'] . "word/\">main vocabulary page</a>.</p>";

		echo "<ul>";
		echo "<li><a href=\"".Core::constructURL('word', 'search', array(''), 'html')."?s=". Core::escapeHTML($data['word'])."\">" .
				"Search vocabulary for <b>" . Core::escapeHTML($data['word']). "</b></a></li>";
		if($permissions['create']) {
			echo "<li><a href=\"".Core::constructURL('word', 'create', array($data['word']), 'html')."\">" .
					"Add the word <b>" . Core::escapeHTML($data['word']). "</b></a></li>";
		}
		echo "</ul>";
	} else {
		echo "<p>The page you are trying to access does not exist. Please return to the <a href=\"" . self::$config['webroot'] . "word/\">main vocabulary page</a></p>";
	}
}
?>
