<? echo word_view::toHTML($data['word']); ?>
<hr />
<dl>
	<dt>For the usage</dt>
	<dd>
		<i><?
			echo core::escapeHTML($data['def']['rel_type']['type_abbr']);
		?></i>
		<? 
			$dest = core::constructURL('word', 'edit', array($data['word']['word_id'], 'def', $data['def']['def_id']), 'html');
			echo core::escapeHTML($data['def']['def_en']);
			echo " <a href=\"$dest\">[edit definition]</a>";
		?>
	</dd>
	<dt>Current examples:</dt>
	<dd>...</dd>
	<dt>All examples containing this word:</dt>
	<dd>...</dd>
</dl>
<? //print_r($data) ?>
