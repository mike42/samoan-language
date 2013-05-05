<?php
if(isset($data['letters'])) {
	foreach($data['letters'] as $l) {
		echo "<div style=\"clear:both; padding-bottom: 1.5em;\">" .
			"<h2><a id=\"" . strtolower($l['letter']) . "\">" . strtoupper($l['letter']) . " " . strtolower($l['letter']) . "</a></h2>";
		showLetter($l, true);
		echo "</div>\n";
	}
} else {
	showLetter($data, false);
}

function showLetter($data, $internal) {
	echo "<div style=\"float:right; font-weight: heavy; text-align: center; \">".word_view::alphabeticPageLinks("<br />", $internal)."</div>";
	if(isset($data['words'])) {
		foreach($data['words'] as $word) {
			echo word_view::toHTML($word);
		}
	} else {
		echo "No words";
	}
}
?>
