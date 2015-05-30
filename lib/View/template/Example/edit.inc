<?php
namespace SmWeb;
?>
<script type="text/javascript">
	function previewExample() {
		$('#action').val('preview');
		$('#example-form').submit();
	}

	function deleteExample() {
		if(confirm('Are you sure you want to delete this example? It could be used on pages or in the vocabulary.')) {
			$('#action').val('delete');
			$('#example-form').submit();
		}
	}
</script>

<?php	echo "<dl><dt>Example #<a href=\"".Core::constructURL('example', 'view', array($data['example']['example_id']), 'html')."\">".$data['example']['example_id']."</a>:";
echo "<dd>".Example_View::toHTML($data['example'], true). ": ";
echo Core::escapeHTML($data['example']['example_en'])."</dd></dl>";
	echo "<hr />" ?>

<form method="post" class="neattable" id="example-form"
	action="<?echo Core::constructURL('example', 'edit', array($data['example']['example_id']), 'html'); ?>">
	<input type="hidden" name="action" id="action" value="save">
	<table>

		<tr>
			<th>Samoan</th>
			<td><textarea name="example_str" rows=4 cols=60><? echo Core::escapeHTML($data['example']['example_str']); ?></textarea></td>
		</tr>

		<tr>
			<th>English</th>
			<td><textarea name="example_en" rows=4 cols=60><? echo Core::escapeHTML($data['example']['example_en']); ?></textarea></td>
		</tr>

		<tr>
			<td colspan=2 style="text-align: right"><? $dest = Core::constructURL('example', 'view', array($data['example']['example_id']), 'html'); ?>
				<a href="<? echo $dest; ?>">[go back]</a> <? 	if($permissions['delete']) {
					echo "<input type=\"button\" onClick=\"deleteExample()\" class=\"danger\" value=\"Delete\" />";
				}
				?> <input type="button" onClick="previewExample()" value="Preview" />
				<input type="submit" name="save" value="Save" />
			</td>
		</tr>
	</table>
</form>
