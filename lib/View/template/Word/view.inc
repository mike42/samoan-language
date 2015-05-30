<?php
namespace SmWeb;

if(isset($data['word'])) {
	if($permissions['edit']) {
		echo "<div style=\"float:right\"><a href=\"".Core::constructURL('word', 'edit', array($data['id']), 'html')."\">(edit)</a></div>";
	}
	echo Word_View::toHTML($data['word']);
} else {
	echo "Word not found";
}
?>