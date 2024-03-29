<div>
	<table class="word-edit">
		<caption>Word properties</caption>
		<tr>
			<th>Number:</th>
			<td><?php echo (int)$data['word']['word_id']; ?></td>
			<td><?php if($permissions['delete']) {
				$dest = core::constructURL('word', 'edit', array($data['id'], 'delete'), 'html');
				echo "<a href=\"$dest\" style=\"color:#f00\">[delete]</a>";
				  }?></td>
		</tr>
		<tr>
			<th>Spelling:</th>
			<td><a
				href="<?php echo core::constructURL('word', 'view', array($data['id']), 'html'); ?>"><?php echo core::escapeHTML($data['id']); ?>
			</a></td>
			<td><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'move'), 'html'); ?>">[move]</a>
			</td>
		</tr>
		<tr>
			<th>Origin:</th>
			<td><?php 
			if($data['word']['word_origin_lang'] != '') {
				echo listlang_view::externalDef($data['word']['rel_lang'], $data['word']['word_origin_word']);
			} else {
				echo "(not borrowed)";
		 			} ?></td>
			<td><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'origin'), 'html'); ?>">[edit]</a>
			</td>
		</tr>
		<tr>
			<th>See:</th>
			<td><?php 
			if($data['word']['word_redirect_to'] != '0') {
				echo word_view::linkToWord($data['word']['rel_target'], false, false, true);
			} else {
				echo "(not a redirect)";
		 			} ?></td>
			<td><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'redirect'), 'html'); ?>">[redirect]</a>
			</td>
		</tr>

	</table>

	<table class="word-edit">
		<caption>Definitions</caption>
		<?php 
		foreach($data['word']['rel_def'] as $id => $def) {
			echo "<tr>";
			echo "<th>".self::$roman_numerals[$id].".</th>";
			echo "<td style=\"text-align: left\"><i>".$def['rel_type']['type_abbr']."</i> ".$def['def_en']."</td>";
			echo "<td><a href=\"".core::constructURL('word', 'edit', array($data['id'], 'def', $def['def_id']), 'html') ."\">[edit]</a></td>";
			echo "<td><a href=\"".core::constructURL('word', 'edit', array($data['id'], 'example', $def['def_id']), 'html') ."\">[examples]</a></td>";
			echo "</tr>\n";
		}
		?>

		<tr>
			<td colspan="4" style="text-align: center"><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'def'), 'html'); ?>">[add
					new definition]</a></td>
		</tr>
	</table>

	<table class="word-edit">
		<caption>Relatives</caption>
		<?php
		foreach($data['word']['rel_words'] as $type => $rel) {
			echo "<tr>";
			echo "<td>".$rel[0]['rel_type_long_label']."</td>";
			/* Get words in this relation */
			$words = array();
			foreach($rel as $word) {
				$words[] = self::linkToWord($word['word'], false, false, true);
			}

			echo "<td>".implode(", ", $words)."</td>";
			echo "<td><a href=\"".core::constructURL('word', 'edit', array($data['id'], 'rel', $type), 'html') ."\">[edit]</a></td>";
			echo "</tr>\n";
		}
		?>

		<tr>
			<td colspan="3"><a
				href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'rel'), 'html'); ?>">[add
					new relatives]</a></td>
		</tr>
	</table>
</div>
