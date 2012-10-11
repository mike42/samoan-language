<?php
if(isset($data['example'])) {
	if($permissions['edit']) {
		/* Show edit button */
		echo "<div style=\"float:right\"><a href=\"".core::constructURL('example', 'edit', array((int)$data['example']['example_id']), 'html')."\">(edit)</a></div>";
	}
	echo "<dl><dt>Example #<a href=\"".core::constructURL('example', 'view', array($data['example']['example_id']), 'html')."\">".$data['example']['example_id']."</a>:";
	echo "<dd>".example_view::toHTML($data['example'], true). ": ";
	echo core::escapeHTML($data['example']['example_en'])."</dd>";
} else {
	if($permissions['create']) {
		/* The 'add example' form */
		echo "<form action=\"".core::constructURL('example', 'create', array(''), 'html')."\" method=\"post\">";
		echo "<p>Enter an example to add it to the database:</p>";
		echo "<table>";
		echo "<tr><td>Samoan:</td><td><input name=\"example_str\" type=\"text\" /></td></tr>";
		echo "<tr><td>English:</td><td><input name=\"example_en\" type=\"text\" /></tr>";
		echo "<tr><td colspan=2 style=\"text-align: right\"><input type=\"submit\" value=\"Add\" />";
		echo "</table>";
		echo "</form>";
	} else {
		echo "<p>This section is for managing examples.</p>";
		echo "<p>You will need be <a href=\"".core::constructURL('user', 'login', array(), 'html'). "\">logged in</a> and have permission to see the edit controls.</p>";
	}
	echo "";
}
?>