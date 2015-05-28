<?php
namespace SmWeb;

if(isset($data['word'])) {
	if($permissions['edit']) {
		echo "<div style=\"float:right\"><a href=\"".core::constructURL('word', 'edit', array($data['id']), 'html')."\">(edit)</a></div>";
	}
	echo word_view::toHTML($data['word']);
} else {
	echo "Word not found";
}
?>