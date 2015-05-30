<?php
namespace SmWeb;

if(isset($data['words'])) {
	foreach($data['words'] as $word) {
		echo Word_View::toHTML($word);
	}
} else {
	echo "No words";
}
?>