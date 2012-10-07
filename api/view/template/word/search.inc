<?php
$count = count($data['words']);
if($count == 0) {
	echo "No results were found!";
} else {
	echo "<i>Found $count word".($count > 1? "s":"")." matching your search:</i><hr />";
	foreach($data['words'] as $word) {
		echo word_view::toHTML($word);
	}
}
?>