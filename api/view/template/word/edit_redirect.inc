<form method="post"
	action="<? echo core::constructURL("word", "edit", array($data['id'], "redirect"), "html") ?>">
	<table class="word-edit">
		<caption>Redirect a word</caption>
		<tr>
			<th>Word to refer to:</th>
			<td><input type="text" name="word_redirect_to"
				value="<?php
					$spelling_t_style	= $data['word']['rel_target']['rel_spelling']['spelling_t_style'];
					$word_num			= $data['word']['rel_target']['word_num'];
					echo core::escapeHTML(word_model::getIdStrBySpellingNum($spelling_t_style, $word_num));
					?>" /> <?php  if(isset($data['message'])) {
						echo "<div style=\"color: #f00\">".core::escapeHTML($data['message'])."</div>";
}?></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right"><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id']), 'html'); ?>">[back
					to word]</a><input type="submit" value="Save"></td>
		</tr>
	</table>
</form>
