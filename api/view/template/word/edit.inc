<div class="editform">
	<table>
		<caption>Word properties</caption>
		<tr>
			<td>
		 		Number:
		 	</td>
		 	<td>
		 		<?php  echo $data['word']['word_id']; ?>
		 	</td>
		 	<td>
		 		<a href="<?php echo core::constructURL('word', 'delete', array($data['id']), 'html'); ?>" style="color:#f00">[delete]</a>
		 	</td>
		</tr>
		<tr>
			<td>
		 		Spelling:
		 	</td>
		 	<td>
		 		<a href="<?php echo core::constructURL('word', 'view', array($data['id']), 'html'); ?>"><? echo $data['id']; ?></a>
		 	</td>
		 	<td>
		 		<a href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'move'), 'html'); ?>">[move]</a>
		 	</td>
		</tr>
		<tr>
			<td>
		 		Origin:
		 	</td>
		 	<td>
		 		...
		 	</td>
		 	<td>
		 		<a href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'origin'), 'html'); ?>">[edit]</a>
		 	</td>
		</tr>
		<tr>
			<td>
		 		See:
		 	</td>
		 	<td>
		 		...
		 	</td>
		 	<td>
		 		<a href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'redirect'), 'html'); ?>">[move]</a>
		 	</td>
		</tr>
		
	</table>
	
	<table>
		<caption>Definitions</caption>
		<?php 
		foreach($data['word']['rel_def'] as $id => $def) {
				echo "<tr>";
				echo "<td>".self::$roman_numerals[$id].".</td>";
				echo "<td><i>".$def['rel_type']['type_abbr']."</i> ".$def['def_en'].".</td>";
				echo "<td><a href=\"".core::constructURL('word', 'edit', array($data['id'], 'def', $def['def_id']), 'html') ."\">[edit]</a></td>";
				echo "</tr>\n";
		}
		?>

		<tr>
			<td colspan="3" style="text-align: center">
				<a href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'def'), 'html'); ?>">[add new definition]</a>
			</td>
		</tr>
	</table>
	
	<table>
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
			<td colspan="3">
				<a href="<?php echo core::constructURL('word', 'edit', array($data['id'], 'rel'), 'html'); ?>">[add new relatives]</a>
			</td>
		</tr>
	</table>
</div>