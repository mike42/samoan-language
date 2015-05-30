<?php
namespace SmWeb;
?>
<form method="post"
	action="<?php echo Core::constructURL("word", "edit", array($data['id'], "origin"), "html") ?>">
	<table class="word-edit">
		<caption>Change word origin</caption>
		<tr>
			<th>Language</th>
			<td><?php echo ListLang_View::makeComboBox($data['listlang'], 'lang_id', $data['word']['word_origin_lang']); ?>
			</td>
		</tr>
		<tr>
			<th>Original word</th>
			<td><input type="text" name="word_origin_word"
				value="<?php echo Core::escapeHTML($data['word']['word_origin_word']); ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a
				href="<?php echo Core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back
					to word]</a><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>
