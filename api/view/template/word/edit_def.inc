<script type="text/javascript">
function delWord() {
	if(confirm("Delete this definition?")) {
		document.getElementById("action").value="delete";
		document.getElementById("def-form").submit();
	}
}
</script>

<form method="post" id="def-form"
	action="<? echo core::constructURL("word", "edit", array($data['id'], 'def', $data['def']['def_id']), 'html'); ?>">
	<input type="hidden" id="action" name="action" value="update" />
	<table class="word-edit">
		<caption>Definition details</caption>
		<tr>
			<th>Type</th>
			<td><?php 
			/* Show the type-selector with the current type selected */
				echo listtype_view::makeComboBox($data['listtype'], 'type_id', $data['def']['def_type']);	?>
			</td>
		</tr>
		<tr>
			<th>English meaning</th>
			<td><textarea rows="2" cols="70" name="def_en" style="resize: none;"><?php echo core::escapeHTML($data['def']['def_en']); ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back
					to word]</a> <input class="danger" type="button" value="Delete"
				onClick="delWord();"> <input type="submit" value="Save"></td>
		</tr>
	</table>
</form>
