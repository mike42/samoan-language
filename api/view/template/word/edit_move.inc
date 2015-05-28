<?php
namespace SmWeb;
?>
<form method="post"
	action="<?php echo core::constructURL("word", "edit", array($data['id'], "move"), "html") ?>">
	<table class="word-edit">
		<caption>Change spelling</caption>
		<tr>
			<th>New spelling:</th>
			<td><input type="text" name="spelling_t_style"
				value="<?php echo core::escapeHTML($data['word']['rel_spelling']['spelling_t_style']); ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back
					to word]</a><input type="submit" value="Move word"></td>
		</tr>
	</table>
</form>
