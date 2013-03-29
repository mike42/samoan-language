<?php
if($data['search'] == '') {
	echo "<p>Enter a Samoan word in the box below to locate its entry in the vocabulary:</p>";
	echo "<form id=\"searchform\" action=\"".core::constructURL('word', 'search', array(''), 'html')."\">";
	echo "<input id=\"search\" name=\"s\" type=\"text\" />";
	echo "<input type=\"submit\" value=\"Search\"/>";
	echo "</form>";
} else {
	$count = count($data['words']);
	echo "<i>Found $count word".($count != 1? "s":"")." matching your search:</i><hr />";
	if($count == 0) {
		$simple = strtolower(spelling_model::calcSimple($data['search']));
		if(strlen($simple) != '') {
			$letter = substr($simple, 0, 1);
			if(in_array($letter, core::$alphabet_en)) {
				echo "<p>You could try looking here:</p>";
				$dest = core::constructURL('word', 'letter', array($letter), 'html');
				echo "<ul><li><a href=\"$dest\">Show all words beginning with ".strtoupper($letter)."</a></li></ul>";
			}
		}
	} else {
		foreach($data['words'] as $word) {
			echo word_view::toHTML($word);
		}
	}
}
?>
