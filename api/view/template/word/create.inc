<form method="post" action="<? echo core::constructURL("word", "create", array(''), "html") ?>">
	<input type="hidden" name="confirm" value="yes" />
	
	<table class="word-edit">
		<caption>Create</caption>
		<tr>
			<th>Spelling of new word:</th>
			<td><input type="text" name="word_redirect_to" value="<?php echo core::escapeHTML($data['spelling_t_style']); ?>" /></td>
		</tr>
		
		<tr>
			<td colspan="2" style="text-align: right">
				<input type="submit" value="Create">
			</td>
		</tr>
	
	</table>
</form>
