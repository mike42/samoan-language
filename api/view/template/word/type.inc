<?php
namespace SmWeb;

if(isset($data['words'])) {
	foreach($data['words'] as $word) {
		echo word_view::toHTML($word);
	}
} else {
	echo "No words";
}
?>