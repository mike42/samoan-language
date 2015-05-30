<?php
namespace SmWeb;

if($permissions['edit']) {
	/* Show edit button */
	echo "<div style=\"float:right\"><a href=\"".Core::constructURL('example', 'edit', array((int)$data['example']['example_id']), 'html')."\">(edit)</a></div>";
}
echo "<dl><dt>Example #<a href=\"".Core::constructURL('example', 'view', array($data['example']['example_id']), 'html')."\">".$data['example']['example_id']."</a>:";
echo "<dd>".Example_View::toHTML($data['example'], true). ": ";
echo Core::escapeHTML($data['example']['example_en'])."</dd></dl>";
?>
