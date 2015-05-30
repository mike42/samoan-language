<?php
namespace SmWeb;
?>
<script type="text/javascript">
	function addExample(id) {
		$('#action').val('add');
		$('#example_id').val(id);
		$('#def-example').submit();
	}

	function delExample(id) {
		$('#action').val('delete');
		$('#example_id').val(id);
		$('#def-example').submit();
	}
</script>
<?php
$formdest = Core::constructURL('word', 'edit', array($data['word']['word_id'], 'example', $data['def']['def_id']), 'html');
echo "<div style=\"float:right\"><a href=\"".Core::constructURL('word', 'edit', array($data['id']), 'html')."\">(back to word)</a></div>";
?>
<form id="def-example" method="post" action="<?php echo $formdest; ?>">
	<input type="hidden" name="action" id="action" value="" /> <input
		type="hidden" name="example_id" id="example_id" value="" />
</form>
<?php echo Word_View::toHTML($data['word']); ?>
<hr />
<p>Definition:</p>
<ul>
	<li><i><?php
	echo Core::escapeHTML($data['def']['rel_type']['type_abbr']);
	?>
	</i> <?php
	$dest = Core::constructURL('word', 'edit', array($data['word']['word_id'], 'def', $data['def']['def_id']), 'html');
	echo Core::escapeHTML($data['def']['def_en']);
	echo " <a href=\"$dest\">[edit]</a>";
	?>
	</li>
</ul>

<h4>Examples</h4>
<?php
echo "<ul>";
if(count($data['def']['rel_example']) == 0) {
	echo "<li>There are no usage examples for this definition.</li>";
} else {
	echo "<ul>";
	foreach($data['def']['rel_example'] as $example) {
		echo "<li>Example #<a href=\"".Core::constructURL('example', 'view', array($example['example_id']), 'html')."\">".$example['example_id']."</a>: ";
		echo Example_View::toHTML($example, true). ": " . Core::escapeHTML($example['example_en']);
		echo " &mdash; <a href=\"#\" onClick=\"delExample(".(int)$example['example_id'].")\">[remove this]</a>";
		echo "</li>";
	}
}
echo "</ul>";
?>

<h4>List of examples which contain this word</h4>
<?php
echo "<ul>";
if(count($data['candidates']) == 0) {
	echo "<li>No examples use this word</li>";
} else {

	foreach($data['candidates'] as $example) {
		echo "<li>Example #<a href=\"".Core::constructURL('example', 'view', array($example['example_id']), 'html')."\">".$example['example_id']."</a>: ";
		echo Example_View::toHTML($example, true). ": " . Core::escapeHTML($example['example_en']);
		echo " &mdash; <a href=\"#\" onClick=\"addExample(".(int)$example['example_id'].")\">[use this]</a>";
		echo "</li>";
	}

}
echo "</ul>";
?>

<h4>Add a different example</h4>

<form class="neattable" method="post" action="<? echo $formdest; ?>">
	<input type="hidden" name="action" value="add" />
	<table>
		<tr>
			<th>Number:</th>
			<td><input type="number" value="" name="example_id"
				style="width: 4em" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a
				href="<?php echo Core::constructURL('example', 'view', array(''), 'html'); ?>"
				target="_new">[search]</a> <input type="submit" value="Add" />
			</td>
		</tr>
	</table>
</form>
