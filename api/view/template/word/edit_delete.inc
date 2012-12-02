<form method="post" action="<? echo core::constructURL("word", "edit", array($data['id'], "delete"), "html") ?>">
	<input type="hidden" name="confirm" value="yes" />
	<table class="word-edit">
		<caption>Confirm deletion</caption>
		<tr><td>Are you sure you want to delete this word?</td></tr>
		<tr>
			<td style="text-align: right"><a href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back to word]</a><input type="submit" style="color:#f00" value="Delete"></td>
		</tr>
	</table>
</form>