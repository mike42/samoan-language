<form method="post" action="<? echo core::constructURL("word", "edit", array($data['id'], 'def', $data['def']['def_id']), 'html'); ?>">
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
			<td>
				<textarea cols="40" rows="2" name="def_en" style="resize: none;"><?php echo core::escapeHTML($data['def']['def_en']); ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back to word]</a><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>
