<?php
if(count($data['examples']) == 0) {
	echo "<i>No matching examples found</i>";
} else {
	foreach($data['examples'] as $example) {
		echo "<dl><dt>Example #<a href=\"".core::constructURL('example', 'view', array($example['example_id']), 'html')."\">".$example['example_id']."</a>:";
		echo "<dd>".example_view::toHTML($example, true). ": ";
		echo core::escapeHTML($example['example_en'])."</dd>";
	}
}
?>
