<form method="post"
	action="<?php echo core::constructURL("word", "edit", array($data['id'], "origin"), "html") ?>">
	<table class="word-edit">
		<caption>Change word origin</caption>
		<tr>
			<th>Language</th>
			<td><?php echo listlang_view::makeComboBox($data['listlang'], 'lang_id', $data['word']['word_origin_lang']); ?>
			</td>
		</tr>
		<tr>
			<th>Original word</th>
			<td><input type="text" name="word_origin_word"
				value="<?php echo core::escapeHTML($data['word']['word_origin_word']); ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back
					to word]</a><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>
