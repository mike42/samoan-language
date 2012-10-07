<?php
echo "<div style=\"float:right; font-weight: heavy; text-align: center; \">".word_view::alphabeticPageLinks("<br />")."</div>";
if(isset($data['words'])) {
	foreach($data['words'] as $word) {
		echo word_view::toHTML($word);
	}
} else {
	echo "No words";
}
?>