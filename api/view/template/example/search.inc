<?php
namespace SmWeb;

if($permissions['create']) {
	echo "<div style=\"float:right\"><a href=\"".core::constructURL('example', 'create', array(''), 'html')."\">(add new)</a></div>";
}
echo "<form id=\"searchform\" action=\"".core::constructURL('example', 'search', array(''), 'html')."\">\n";
echo "<input id=\"search\" name=\"s\" type=\"text\" />";
echo "<input type=\"submit\" value=\"Search\"/></form>";

if($data['search'] != '' && count($data['examples']) == 0) {
	echo "<hr />";
	echo "<i>No matching examples found</i>";
} elseif(count($data['examples']) > 0) {
	echo "<hr />";
	echo "<dl>";
	foreach($data['examples'] as $example) {
		echo "<dt>Example #<a href=\"".core::constructURL('example', 'view', array($example['example_id']), 'html')."\">".$example['example_id']."</a>:";
		echo "<dd>".example_view::toHTML($example, true). ": ";
		echo core::escapeHTML($example['example_en'])."</dd>";
	}
	echo "</dl>";
}
?>
