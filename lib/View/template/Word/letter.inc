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
	echo "<div style=\"float:right; font-weight: heavy; text-align: center; padding-left: 1em; \">".Word_View::alphabeticPageLinks("<br />", $internal)."</div>";

	if(isset($data['cache'])) {
		/* Check whether this data is simply cached output */
		echo $data['cache'];
		return;
	}

	/* Otherwise build output */
	$outp = "";
	if(isset($data['words'])) {
		foreach($data['words'] as $word) {
			$outp .= Word_View::toHTML($word);
		}
	} else {
		$outp = "No words";
	}

	/* Save generated output to cache */
	$lm = Letter_Model::$template;
	$lm['letter_id'] = $data['letter'];
	$lm['letter_html'] = $outp;
	Letter_Model::cache_save($lm);
	echo $outp;
}
?>
