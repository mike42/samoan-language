<form method="post" action="<? echo core::constructURL("word", "edit", array($data['id'], "redirect"), "html") ?>">
	<table class="word-edit">
		<caption>Redirect a word</caption>
		<tr>
			<th>Word to refer to:</th>
			<td><input type="text" name="word_redirect_to" value="" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right">
				<a href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back to word]</a><input type="submit" value="Save">
			</td>
		</tr>
	</table>
</form>