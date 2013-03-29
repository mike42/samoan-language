<div class="editform">
	<form action="<? echo core::constructURL('page', 'edit', array($data['id']), 'html'); ?>" method="post">
		<table>
			<tr>
				<td>Page title:</td>
				<td><input type="text" name="revision_title" value="<?php echo core::escapeHTML($data['page']['page_rel_revision']['revision_title']); ?>"/></td>
			</tr>
			<tr>
				<td colspan=2>
					<textarea id="page-editbox" name="revision_text" cols=65 rows=20><?php echo core::escapeHTML($data['page']['page_rel_revision']['revision_text']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan=2 style="text-align: right">
					<input type="submit" name="submit" value="Preview" />
					<input type="submit" name="submit" value="Save" />
				</td>
			</tr>
		</table>
	</form>
</div>
