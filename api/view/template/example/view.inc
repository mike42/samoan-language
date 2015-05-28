<?php
namespace SmWeb;

if($permissions['edit']) {
	/* Show edit button */
	echo "<div style=\"float:right\"><a href=\"".core::constructURL('example', 'edit', array((int)$data['example']['example_id']), 'html')."\">(edit)</a></div>";
}
echo "<dl><dt>Example #<a href=\"".core::constructURL('example', 'view', array($data['example']['example_id']), 'html')."\">".$data['example']['example_id']."</a>:";
echo "<dd>".example_view::toHTML($data['example'], true). ": ";
echo core::escapeHTML($data['example']['example_en'])."</dd></dl>";
?>
