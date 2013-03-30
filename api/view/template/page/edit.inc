<div class="editform">
	<form action="<? echo core::constructURL('page', 'edit', array($data['id']), 'html'); ?>" method="post">
		<table>
			<tr>
				<td>Page title:</td>
				<td><input type="text" name="revision_title" value="<?php echo core::escapeHTML($data['page']['page_rel_revision']['revision_title']); ?>"/></td>
			</tr>
			<? if(isset($data['preview'])) {
				echo "<div class=\"notice\">Note: This is only a preview, your changes have not been saved!</div>";
				echo "<hr>";
				echo $data['page']['page_rel_revision']['revision_text_parsed'];
				echo "<hr>";
			} ?>
			<tr>
				<td colspan=2>
					<textarea id="page-editbox" name="revision_text" cols=65 rows=20><?php echo core::escapeHTML($data['page']['page_rel_revision']['revision_text']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan=2 style="text-align: right">
					<? $dest = core::constructURL('page', 'view', array($data['id']), 'html'); ?>
					<a href="<? echo $dest; ?>">[cancel edit]</a>
					<input type="submit" name="submit" value="Preview" />
					<input type="submit" name="submit" value="Save" />
				</td>
			</tr>
		</table>
	</form>
</div>
