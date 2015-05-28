<?php
namespace SmWeb;

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
	echo "<div style=\"float:right; font-weight: heavy; text-align: center; padding-left: 1em; \">".word_view::alphabeticPageLinks("<br />", $internal)."</div>";

	if(isset($data['cache'])) {
		/* Check whether this data is simply cached output */
		echo $data['cache'];
		return;
	}

	/* Otherwise build output */
	$outp = "";
	if(isset($data['words'])) {
		foreach($data['words'] as $word) {
			$outp .= word_view::toHTML($word);
		}
	} else {
		$outp = "No words";
	}

	/* Save generated output to cache */
	$lm = letter_model::$template;
	$lm['letter_id'] = $data['letter'];
	$lm['letter_html'] = $outp;
	letter_model::cache_save($lm);
	echo $outp;
}
?>
