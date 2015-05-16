<?php
/* The 'add example' form */
echo "<form action=\"".core::constructURL('example', 'create', array(''), 'html')."\" method=\"post\">";
echo "<p>Enter an example to add it to the database:</p>";
echo "<table>";
echo "<tr><td>Samoan:</td><td><input name=\"example_str\" type=\"text\" /></td></tr>";
echo "<tr><td>English:</td><td><input name=\"example_en\" type=\"text\" /></tr>";
echo "<tr><td colspan=2 style=\"text-align: right\"><input type=\"submit\" value=\"Add\" />";
echo "</table>";
echo "</form>";
?>
